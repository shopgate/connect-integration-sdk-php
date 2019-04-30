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

namespace Shopgate\ConnectSdk\Services\Events\Connector\DTO\Payload;

use Exception;
use Psr\Http\Message\ResponseInterface;

class Base
{
    /**
     * @param string $name
     * @param array  $args
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function __call($name, $args = [])
    {
        if (empty($args) || count($args) > 3) {
            throw new Exception('Invalid amount of parameters provided');
        }
        //todo-sg: test weird stuff, make sure it allows just 'update' instead of updateCategory
        list($method, $folder) = preg_split('/(?=[A-Z])/', $name);
        $structure = $folder . '\\' . ucfirst($method); //todo-sg: cleanup

        return $this->instantiateClass($structure)->hydrate(...$args);
    }

    /**
     * @param string $folder - name of the folder the entity resides
     *
     * @return mixed
     * @throws Exception
     */
    protected function instantiateClass($folder)
    {
        $folder  = $folder ? '\\' . ucfirst($folder) : '';
        $current = '\\' . substr(strrchr(static::class, "\\"), 1);
        $class   = 'Shopgate\ConnectSdk\Services\Events\DTO\Payload' . $current . $folder;
        if (class_exists($class)) {
            return new $class();
        }
        //todo-sg: custom exception for entities
        throw new Exception('Entity does not exist');
    }
}
