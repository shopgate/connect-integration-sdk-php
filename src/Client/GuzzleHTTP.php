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

namespace Shopgate\CloudIntegrationSdk\Client;

use GuzzleHttp as Guzzle;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface;

class GuzzleHttp implements ClientInterface
{
    const CONFIG_KEY_AUTHENTICATION          = 'auth';
    const CONFIG_KEY_AUTHENTICATION_USER     = 'user';
    const CONFIG_KEY_AUTHENTICATION_PASSWORD = 'pass';

    /** @var Guzzle\ClientInterface */
    private $guzzleClient;

    /** @var string|null */
    private $authentication;

    /** @var array */
    private $config;

    /**
     * @param string $authentication Authentication mode, e.g. 'basic'
     * @param array  $config
     */
    public function __construct($authentication = null, array $config = [])
    {
        $this->guzzleClient   = new Guzzle\Client();
        $this->authentication = $authentication;
        $this->config         = $config;
    }

    /**
     * @inheritdoc
     *
     * @throws GuzzleException
     */
    public function request(RequestInterface $request, array $options = [])
    {
        $options = $this->setAuthenticationHeader($options);

        return $this->guzzleClient->send($request, $options);
    }

    /**
     * @inheritdoc
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function setAuthenticationHeader($options)
    {
        if (!isset($this->config[self::CONFIG_KEY_AUTHENTICATION])) {
            return $options;
        }

        $authenticationData = $this->config[self::CONFIG_KEY_AUTHENTICATION];

        if ($this->getAuthentication() === ClientInterface::AUTHENTICATION_TYPE_BASIC
            && isset(
                $authenticationData[self::CONFIG_KEY_AUTHENTICATION_PASSWORD],
                $authenticationData[self::CONFIG_KEY_AUTHENTICATION_USER]
            )
        ) {
            $options[Guzzle\RequestOptions::AUTH] = [
                $authenticationData[self::CONFIG_KEY_AUTHENTICATION_USER],
                $authenticationData[self::CONFIG_KEY_AUTHENTICATION_PASSWORD]
            ];
        }

        return $options;
    }
}
