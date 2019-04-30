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

namespace Shopgate\ConnectSdk\Services\Events\Connector\Entities;

use Exception;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Services\Events\Entities\EntityInterface;

class Base
{
    const KEY_TYPE = 'requestType';
    const SYNC     = 'direct';
    const ASYNC    = 'async';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param array|mixed $config
     *
     * @return bool
     */
    protected function isDirect($config)
    {
        return is_array($config) && isset($config[self::KEY_TYPE]) && $config[self::KEY_TYPE] === self::SYNC;
    }

    /**
     * @param string $folder - name of the folder the entity resides
     * @param bool   $isDirect
     *
     * @return EntityInterface
     * @throws Exception
     */
    protected function instantiateClass($folder, $isDirect = false)
    {
        $folder  = $folder ? '\\' . ucfirst($folder) : '';
        $direct  = '\\' . ucfirst($isDirect ? self::SYNC : self::ASYNC);
        $current = '\\' . substr(strrchr(static::class, "\\"), 1);
        $class   = 'Shopgate\ConnectSdk\Services\Events\Entities' . $current . $folder . $direct;
        if (class_exists($class)) {
            return new $class($this->client);
        }
        //todo-sg: custom exception for entities
        throw new Exception('Entity does not exist');
    }
}
