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

namespace Shopgate\ConnectSdk\DTO;

use Dto\Dto;
use Dto\Exceptions\InvalidDataTypeException;
use Dto\Exceptions\InvalidIndexException;
use Dto\RegulatorInterface;
use Exception;

/**
 * @codeCoverageIgnore
 */
class Base extends Dto
{
    /**
     * Rewritten to provide inheritance of payload structure
     *
     * @inheritDoc
     */
    public function __construct($input = null, $schema = null, RegulatorInterface $regulator = null)
    {
        parent::__construct($input, null === $schema ? $this->getDefaultSchema() : $schema, $regulator);
    }

    /**
     * Use this to inject schema if property rewrite is not enough
     *
     * @return array|null
     */
    public function getDefaultSchema()
    {
        return null;
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return  mixed
     * @throws InvalidDataTypeException
     * @throws Exception
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = lcfirst(substr($method, 3));

                return $this->get($key);
            case 'set':
                $key = lcfirst(substr($method, 3));

                return $this->set($key, isset($args[0]) ? $args[0] : null);
        }
        $error = 'Invalid method ' . get_class($this) . '::' . $method . '(' . print_r($args, 1) . ')';
        throw new InvalidIndexException($error);
    }

    /**
     * Rewritten to return the object for chaining purposes
     *
     * @inheritDoc
     * @return Base
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        return $this;
    }

    /**
     * Rewritten to return the object for chaining purposes
     *
     * @inheritDoc
     * @return Base
     */
    public function get($key)
    {
        /**
         * @var Dto $result
         */
        $result = parent::get($key);

        if ($result->getStorageType() == "scalar") {
            return $result->toScalar();
        }

        return $result;
    }

    /**
     * @inheritDoc
     *
     * @return Base
     */
    public function hydrate($value)
    {
        parent::hydrate($value);

        return $this;
    }

    public function __toString()
    {
        return parent::toJson(true);
    }
}
