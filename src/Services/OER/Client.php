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

namespace Shopgate\ConnectSdk\Services\OER;

use Exception;
use Shopgate\ConnectSdk\Http;

/**
 * @property Entities\Catalog catalog
 */
class Client
{
    /** @var Http\ClientInterface */
    private $client;

    /**
     * Contains entities to use, e.g. catalog, product, media, etc.
     *
     * @var array
     */
    private $entities = [];

    /**
     * This client accepts the following options:
     *  - http_client (Http\ClientInterface, default=Http\GuzzleClient) - accepts a custom HTTP client if needed
     *  - auth (array) authentication data necessary for the client to make calls
     *
     * @param array $config
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $config)
    {
        $this->client = isset($config['http_client']) && $config['http_client'] instanceof Http\ClientInterface
            ? $config['http_client']
            : new Http\GuzzleClient($config);
    }

    /** @noinspection MagicMethodsValidityInspection */
    /**
     * For direct objects calls like $sdk->catalog->update()
     *
     * @param string $name
     *
     * @return Entities\EntityInterface
     * @throws Exception
     */
    public function __get($name)
    {
        if (isset($this->entities[$name])) {
            return $this->entities[$name]();
        }
        $class = 'Shopgate\ConnectSdk\Services\OER\Entities\\' . ucfirst($name);
        if (class_exists($class)) {
            $this->entities[$name] = new $class($this->client);
        } else {
            //todo-sg: custom exception for entities
            throw new Exception('Entity does not exist');
        }

        return $this->entities[$name];
    }
}
