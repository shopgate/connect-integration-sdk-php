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
use Shopgate\ConnectSdk\Connector_\Entities\Base;
use Shopgate\ConnectSdk\Exceptions\ClassNoExistException;
use Shopgate\ConnectSdk\Http;
use Shopgate\ConnectSdk\Http\ClientInterface;

class ShopgateSdk
{
    /** @var Base */
    public $catalog;

    /** @var ClientInterface */
    private $client;

    /**
     * @param array $config
     *
     * @codeCoverageIgnore
     * @throws ClassNoExistException
     */
    public function __construct(array $config)
    {
        $configResolver   = new Config();
        $options          = $configResolver->resolveMainOptions($config);
        $options['oauth'] = $configResolver->resolveOauthOptions($options['oauth']);

        $this->client = isset($options['http_client'])
            ? $options['http_client']
            : new Http\GuzzleClient($options);

        $this->catalog = $this->instantiateClass('catalog');
    }

    /**
     * A factory for connector classes
     *
     * @param string $name
     *
     * @return Base
     * @throws ClassNoExistException
     */
    private function instantiateClass($name)
    {
        $class = 'Shopgate\ConnectSdk\Entities\\' . ucfirst($name);
        if (class_exists($class)) {
            return new $class($this->client);
        }
        throw new ClassNoExistException('Connector does not exist');
    }

    /**
     * @param TokenPersistenceInterface $storage
     */
    public function setStorage(TokenPersistenceInterface $storage)
    {
        /** @var HandlerStack $handler */
        $handler = $this->client->getConfig('handler');
        $handler->remove('OAuth2');
        $oauth      = new Http\OAuth($this->client->getConfig('oauth'));
        $middleware = $oauth->getOauthMiddleware();
        $middleware->setTokenPersistence($storage);
        $handler->push($middleware, 'OAuth2.custom');
    }
}
