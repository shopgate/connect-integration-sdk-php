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

namespace Shopgate\ConnectSdk\Services\Events\Connector;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Services\Events\Entities\EntityInterface;

/**
 * @method ResponseInterface updateCategory(string $entityId, array $payload, array $meta)
 */
class Catalog
{
    use BaseTrait;

    /**
     * @param string $name
     * @param array  $args
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function __call($name, $args = [])
    {
        if (count($args) > 3) {
            throw new Exception('Too many parameters passed');
        }
        //todo-sg: test weird stuff
        list($method, $class) = preg_split('/(?=[A-Z])/', $name);

        //todo-sg: test different amount of params and errors
        $direct = $this->isDirect($args[0]) || $this->isDirect($args[2]);

        return $this->instantiateClass($class, $direct)->$method(...$args);
    }

    /**
     * @param array|mixed $config
     *
     * @return bool
     */
    protected function isDirect($config)
    {
        return is_array($config) && isset($config['requestType']) && $config['requestType'] === 'direct';
    }

    /**
     * @param string $name
     * @param bool   $isDirect
     *
     * @return EntityInterface
     * @throws Exception
     */
    private function instantiateClass($name, $isDirect = false)
    {
        $direct = $isDirect ? 'Direct\\' : 'Async\\';
        $class  = 'Shopgate\ConnectSdk\Services\Events\Entities\\' . $direct . ucfirst($name);
        if (class_exists($class)) {
            return new $class($this->client);
        }
        //todo-sg: custom exception for entities
        throw new Exception('Entity does not exist');
    }
}
