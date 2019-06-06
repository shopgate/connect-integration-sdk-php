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

namespace Shopgate\ConnectSdk\Connector_\Entities;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Connector_\Utility;
use Shopgate\ConnectSdk\Entities\AsyncEntityInterface;
use Shopgate\ConnectSdk\Exceptions\IncorrectArgsException;
use Shopgate\ConnectSdk\Http\ClientInterface;

class Base
{
    use Utility;

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
     * @param string $name
     * @param array  $args
     *
     * @return ResponseInterface
     * @throws Exception
     * @uses \Shopgate\ConnectSdk\Entities\Catalog\Category\Async::update()
     * @uses \Shopgate\ConnectSdk\Entities\Catalog\Category\Async::create()
     * @uses \Shopgate\ConnectSdk\Entities\Catalog\Category\Async::delete()
     * @uses \Shopgate\ConnectSdk\Entities\Catalog\Category\Direct::update()
     */
    public function __call($name, $args = [])
    {
        if (empty($args) || count($args) > 3) {
            throw new IncorrectArgsException('Invalid amount of parameters provided');
        }

        list($method, $folder) = $this->splitMethodName($name);
        $direct = $method === 'get' || $this->isDirect($args[count($args) - 1]);

        return $this->instantiateClass($folder, $direct)->$method(...$args);
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
     * @param string|null $folder - name of the folder the entity resides
     * @param bool        $isDirect
     *
     * @return AsyncEntityInterface
     * @throws Exception
     */
    protected function instantiateClass($folder, $isDirect = false)
    {
        $current = $this->getClassPath($folder);
        $direct  = '\\' . ucfirst($isDirect ? self::SYNC : self::ASYNC);
        $class   = 'Shopgate\ConnectSdk\Entities' . $current . $direct;

        return $this->getClass($class, [$this->client]);
    }
}
