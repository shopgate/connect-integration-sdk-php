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

namespace Shopgate\ConnectSdk;

use GuzzleHttp\HandlerStack;
use kamermans\OAuth2\Persistence\TokenPersistenceInterface;
use Shopgate\ConnectSdk\Entities\Catalog;
use Shopgate\ConnectSdk\Http;

class ShopgateSdk
{
    const REQUEST_TYPE_DIRECT = "direct";
    const REQUEST_TYPE_EVENT = "event";

    /** @var Catalog */
    public $catalog;
    /** @var Http\ClientInterface */
    protected $httpClient;
    /** @var ClientInterface */
    private $client;

    /**
     * @param array $config
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $config)
    {
        $configResolver   = new Config();
        $options          = $configResolver->resolveMainOptions($config);
        $options['oauth'] = $configResolver->resolveOauthOptions($options['oauth']);
        $this->httpClient = isset($options['http_client'])
            ? $options['http_client']
            : new Http\GuzzleClient($options);
        $this->client     = new Client($this->httpClient);

        $this->catalog = $this->instantiateClass('catalog');
    }

    /**
     * A factory for connector classes
     *
     * @param string $name
     *
     * @return mixed
     */
    private function instantiateClass($name)
    {
        $class = 'Shopgate\ConnectSdk\Entities\\' . ucfirst($name);

        return new $class($this->client);
    }

    /**
     * @todo-sg: test external setter
     *
     * @param TokenPersistenceInterface $storage
     */
    public function setStorage(TokenPersistenceInterface $storage)
    {
        /** @var HandlerStack $handler */
        $handler = $this->httpClient->getConfig('handler');
        $handler->remove('OAuth2');
        $oauth      = new Http\OAuth($this->httpClient->getConfig('oauth'));
        $middleware = $oauth->getOauthMiddleware();
        $middleware->setTokenPersistence($storage);
        $handler->push($middleware, 'OAuth2.custom');
    }
}
