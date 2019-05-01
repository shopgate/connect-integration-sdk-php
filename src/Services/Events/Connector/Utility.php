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

trait Utility
{
    /**
     * Will break updateCatalog to ['update', 'catalog'] or update to ['update']
     *
     * @param string $name
     *
     * @return array
     */
    public function splitMethodName($name)
    {
        return array_map('lcfirst', preg_split('/(?=[A-Z])/', $name));
    }

    /**
     * @param string|null $folder
     *
     * @return string
     */
    public function getClassPath($folder)
    {
        $folder  = empty($folder) ? '' : '\\' . ucfirst($folder);
        $current = '\\' . substr(strrchr(static::class, '\\'), 1);

        return $current . $folder;
    }

    /**
     * @param string $class           - FQN class
     * @param array  $constructorArgs - pass arguments to the constructor
     *
     * @return mixed
     * @throws Exception
     */
    public function getClass($class, array $constructorArgs = [])
    {
        if (class_exists($class)) {
            return new $class(...$constructorArgs);
        }
        //todo-sg: custom exception for entities
        throw new Exception('Entity does not exist');
    }
}
