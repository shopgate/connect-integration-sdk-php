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
use Psr\Http\Message\ResponseInterface;

/**
 * @method ResponseInterface updateCategory(string $entityId, array $payload, array $meta)
 */
class Catalog extends Base
{
    /**
     * @param string $name
     * @param array  $args
     *
     * @return ResponseInterface
     * @throws Exception
     * @uses \Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category\Async::update()
     * @uses \Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category\Direct::update()
     */
    public function __call($name, $args = [])
    {
        if (empty($args) || count($args) > 3) {
            throw new Exception('Invalid amount of parameters provided');
        }
        //todo-sg: test weird stuff, make sure it allows just 'update' instead of updateCategory
        list($method, $folder) = preg_split('/(?=[A-Z])/', $name);

        //todo-sg: test different amount of params and possible errors
        $direct = $this->isDirect($args[count($args) - 1]);

        return $this->instantiateClass($folder, $direct)->$method(...$args);
    }
}
