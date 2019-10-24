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

namespace Shopgate\ConnectSdk\Dto;

use Dto\Dto;
use Dto\RegulatorInterface;
use Dto\ServiceContainer;
use Exception;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

/**
 * @codeCoverageIgnore
 */
abstract class Base extends Dto
{
    const STORAGE_TYPE_SCALAR = 'scalar';
    const STORAGE_TYPE_ARRAY = 'array';

    /**
     * Rewritten to provide inheritance of payload structure
     *
     * @inheritDoc
     *
     * @throws InvalidDataTypeException
     */
    public function __construct($input = null, $schema = null, RegulatorInterface $regulator = null)
    {
        try {
            /**
             * The property 'skipValidation' is passed along to all child definitions.
             * 'skipValidation' also should skip the conversion of property values
             */
            if (isset($schema['skipValidation']) && isset($schema['type'])) {
                unset($schema['type']);
            }

            /**
             * Null values are skipped to simplify validation.
             */
            if (isset($schema['type']) && !is_array($schema['type']) && $input !== null) {
                switch ($schema['type']) {
                    case 'array':
                        if (!is_array($input)) {
                            throw new InvalidDataTypeException('Incorrect data type: Expected array, but got: ' . gettype($input) . ' in ' . get_class($this));
                        }
                        break;
                    case 'integer':
                        if (!is_int($input)) {
                            throw new InvalidDataTypeException('Incorrect data type: Expected integer, but got: ' . gettype($input) . ' in ' . get_class($this));
                        }
                        break;
                    case 'number':
                        if (!is_numeric($input)) {
                            throw new InvalidDataTypeException('Incorrect data type: Expected number, but got: ' . gettype($input) . ' in ' . get_class($this));
                        }
                        break;
                    case 'string':
                        if (!is_string($input)) {
                            throw new InvalidDataTypeException('Incorrect data type: Expected string, but got: ' . gettype($input) . ' in ' . get_class($this));
                        }
                        break;
                    case 'boolean':
                        if (!is_bool($input)) {
                            throw new InvalidDataTypeException('Incorrect data type: Expected boolean, but got: ' . gettype($input) . ' in ' . get_class($this));
                        }
                        break;
                    case 'null':
                        if (!is_null($input)) {
                            throw new InvalidDataTypeException('Incorrect data type: Expected null, but got: ' . gettype($input) . ' in ' . get_class($this));
                        }
                        break;
                }
            }

            parent::__construct($input, null === $schema ? $this->getDefaultSchema() : $schema, $regulator);
        } catch (Exception $exception) {
            throw new InvalidDataTypeException($exception->getMessage());
        }
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
     * @param RegulatorInterface $regulator
     *
     * @return RegulatorInterface
     */
    protected function getDefaultRegulator($regulator)
    {
        if ($regulator === null) {
            return new SchemaRegulator(new ServiceContainer(), static::class);
        }

        return $regulator;
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     *
     * @throws InvalidDataTypeException
     */
    public function __call($method, $args)
    {
        $key = lcfirst(substr($method, 3));
        switch (substr($method, 0, 3)) {
            case 'get':
                return $this->get($key);
            case 'set':
            default:
                return $this->set($key, isset($args[0]) ? $args[0] : null);
        }
    }

    /**
     * Rewritten to return the object for chaining purposes
     *
     * @param $key   mixed
     * @param $value mixed
     *
     * @return $this
     *
     * @throws InvalidDataTypeException
     */
    public function set($key, $value)
    {
        try {
            parent::set($key, $value);
        } catch (Exception $exception) {
            /**
             * This could be for example:
             * - Dto\Exceptions\InvalidDataTypeException : Properties can only be set on objects.
             * - Dto\Exceptions\InvalidKeyException : Key not allowed by "properties", "patternProperties", or
             * "additionalProperties": test
             */
            throw new InvalidDataTypeException($exception->getMessage());
        }

        return $this;
    }

    /**
     * Rewritten to return the object for chaining purposes
     *
     * @inheritDoc
     */
    public function get($key)
    {
        try {
            /** @var Dto $result */
            $result = parent::get($key);

            if ($result->getStorageType() === self::STORAGE_TYPE_SCALAR) {
                return $result->toScalar();
            }

            if ($result->getStorageType() === self::STORAGE_TYPE_ARRAY) {
                $items = [];
                foreach ($result as $item) {
                    /** @var Base $item */
                    if ($item->getStorageType() === self::STORAGE_TYPE_SCALAR) {
                        $items[] = $item->toScalar();
                    } else {
                        $items[] = $item;
                    }
                }
                return $items;
            }

            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        try {
            return parent::toArray();
        } catch (Exception $exception) {
            return [];
        }
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

    /**
     * @return string
     */
    public function __toString()
    {
        return parent::toJson(true);
    }
}
