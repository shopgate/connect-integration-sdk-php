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

namespace Shopgate\CloudIntegrationSdk\Service;

use Shopgate\CloudIntegrationSdk\Repository;
use Shopgate\CloudIntegrationSdk\ValueObject;
use Shopgate\CloudIntegrationSdk\ValueObject\Route;
use Shopgate\CloudIntegrationSdk\ValueObject\RequestMethod;

class Router
{
    /** @var RequestHandler\RequestHandlerInterface[][] */
    private $requestHandlers;

    /** @var UriParser */
    private $uriParser;

    /**
     * Router constructor.
     *
     * @param Repository\AbstractClientCredentials $clientCredentialsRepository
     * @param Repository\AbstractToken             $tokenRepository
     * @param Repository\AbstractUser              $userRepository
     */
    public function __construct(
        Repository\AbstractClientCredentials $clientCredentialsRepository,
        Repository\AbstractToken $tokenRepository,
        Repository\AbstractUser $userRepository
    ) {
        $this->uriParser = new UriParser();

        // add predefined routes
        $this->subscribe(new Route\AuthToken(), new RequestMethod\Get(),
            new RequestHandler\PostAuthToken(
                $clientCredentialsRepository, $tokenRepository, $userRepository
            )
        );
        $this->subscribe(new Route\V2(), new RequestMethod\Get(),
            new RequestHandler\GetV2($clientCredentialsRepository)
        );
    }

    /**
     * @param Route\AbstractRoute $route
     * @param RequestMethod\AbstractRequestMethod $method
     * @param RequestHandler\RequestHandlerInterface $handler
     */
    public function subscribe(
        Route\AbstractRoute $route,
        RequestMethod\AbstractRequestMethod $method,
        RequestHandler\RequestHandlerInterface $handler
    ) {
        $this->uriParser->addRoute($route);
        $this->requestHandlers[$route->getIdentifier()][(string) $method] = $handler;
    }

    /**
     * @param ValueObject\Request $request
     *
     * @return ValueObject\Response
     */
    public function dispatch(ValueObject\Request $request)
    {
        $route = $this->uriParser->getRoute($request->getUri());
        $requestHandler = $this->requestHandlers[$route->getIdentifier()][(string) $request->getMethod()];

        // TODO: Respond with error if no request handler found

        $auth = $requestHandler->getAuthenticator();
        if (!$auth->authenticate($request)) {
            return new ValueObject\Response();
        }

        return $requestHandler->handle($request);
    }
}
