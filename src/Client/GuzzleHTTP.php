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

use GuzzleHttp;
use Psr\Http\Message\RequestInterface;
use Shopgate\CloudIntegrationSdk\Repository\Config\ConfigInterface;

class GuzzleHTTP implements ClientInterface
{
    /** @var GuzzleHttp\ClientInterface */
    private $guzzleClient;

    /** @var string|null */
    private $authentication;

    /** @var ConfigInterface */
    private $config;

    /**
     * @param string            $authentication    Authentication mode, e.g. 'basic'
     * @param ConfigInterface   $config
     */
    public function __construct($authentication = null, ConfigInterface $config)
    {
        $this->guzzleClient = new GuzzleHttp\Client();
        $this->authentication = $authentication;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, array $options = [])
    {
        $options = $this->setAthenticationHeader($options);

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
    private function setAthenticationHeader($options)
    {
        switch ($this->getAuthentication()) {
            case ClientInterface::AUTHENTICATION_TYPE_BASIC:
                // TODO-sg: need to inject auth header from config or maybe service config
                $options[GuzzleHttp\RequestOptions::AUTH] = ['', ''];
                break;
            default:
                break;
        }

        return $options;
    }
}
