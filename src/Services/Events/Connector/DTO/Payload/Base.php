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
use Shopgate\ConnectSdk\Services\Events\Connector\Utility;
use Shopgate\ConnectSdk\Services\Events\DTO\Base as Payload;

class Base
{
    use Utility {
        splitMethodName as traitSplitMethod;
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return Payload
     * @throws Exception
     */
    public function __call($name, $args = [])
    {
        if (empty($args) || count($args) > 3) {
            throw new Exception('Invalid amount of parameters provided');
        }
        //todo-sg: test weird stuff, make sure it allows just 'update' instead of updateCategory
        $folder = $this->namespaceFromMethod($name);

        return $this->instantiateClass($folder)->hydrate(...$args);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    private function namespaceFromMethod($method)
    {
        return implode('\\', array_map('ucfirst', $this->splitMethodName($method)));
    }

    /**
     * @param string $folder - name of the folder the entity resides
     *
     * @return Payload
     * @throws Exception
     */
    protected function instantiateClass($folder)
    {
        $current = $this->getClassPath($folder);
        $class   = 'Shopgate\ConnectSdk\Services\Events\DTO\Payload' . $current;

        return $this->getClass($class);
    }

    /**
     * Rewrite to reverse the split method output
     *
     * @param string $name
     *
     * @return array
     */
    public function splitMethodName($name)
    {
        return array_reverse($this->traitSplitMethod($name));
    }
}
