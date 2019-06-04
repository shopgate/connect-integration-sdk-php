<?php

/**
 * Copyright Shopgate Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Shopgate Inc, 804 Congress Ave, Austin, Texas 78701 <interfaces@shopgate.com>
 * @copyright Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Http;

use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Promise\rejection_for;

/**
 * OAuth2 plugin.
 *
 * @link http://tools.ietf.org/html/rfc6749 OAuth2 specification
 * @codeCoverageIgnore
 */
class OAuth2Middleware extends \kamermans\OAuth2\OAuth2Middleware
{
    /**
     * Guzzle middleware invocation.
     *
     * @param callable $handler
     *
     * @return Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {

            // Only sign requests using "auth"="oauth"
            if (!isset($options['auth']) || $options['auth'] !== 'oauth') {
                return $handler($request, $options);
            }

            /** @var RequestInterface $request */
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $request = $this->signRequest($request);

            return $handler($request, $options)->then(
                $this->onFulfilled($request, $options, $handler),
                $this->onRejected($request, $options, $handler)
            );
        };
    }

    /**
     * Request error event handler.
     *
     * Handles unauthorized errors by acquiring a new access token and
     * retrying the request.
     *
     * @param RequestInterface $request
     * @param array            $options
     * @param                  $handler
     *
     * @return Closure
     */
    protected function onFulfilled(RequestInterface $request, array $options, $handler)
    {
        return function ($response) use ($request, $options, $handler) {
            /** @var ResponseInterface $response */
            if ($response && !in_array($response->getStatusCode(), [401, 403], true)) {
                return $response;
            }

            // If we already retried once, give up.
            // This is extremely unlikely in Guzzle 6+ since we're using promises
            // to check the response - looping should be impossible, but I'm leaving
            // the code here in case something interferes with the Middleware
            if ($request->hasHeader('X-Guzzle-Retry')) {
                return $response;
            }

            // Delete the previous access token, if any
            $this->deleteAccessToken();

            // Acquire a new access token, and retry the request.
            $accessToken = $this->getAccessToken();
            if ($accessToken === null) {
                return $response;
            }

            $request = $request->withHeader('X-Guzzle-Retry', '1');
            $request = $this->signRequest($request);

            return $handler($request, $options);
        };
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     * @param                  $handler
     *
     * @return Closure
     */
    protected function onRejected(RequestInterface $request, array $options, $handler)
    {
        return function ($reason) use ($request, $options) {
            return rejection_for($reason);
        };
    }
}
