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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

class GuzzleClient implements ClientInterface
{
    /**
     * The key for Guzzle authentication
     */
    const CONFIG_KEY_AUTHENTICATION = RequestOptions::AUTH;

    /** @var \GuzzleHttp\ClientInterface */
    private $client;

    /** @var array */
    private $config;

    /**
     * The client accepts the following options:
     *  - auth (array) - a list of credentials as needed by Guzzle, e.g. ["username", "password"] for basic auth
     *  - any other options accepted by Guzzle's request method
     *
     * @see http://docs.guzzlephp.org/en/stable/request-options.html#auth
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * @inheritdoc
     *
     * @throws GuzzleException
     */
    public function request(RequestInterface $request, array $options = [])
    {
        $config = $this->config['http'][self::CONFIG_KEY_AUTHENTICATION] + $options;

        return $this->client->send($request, $config);
    }
}
