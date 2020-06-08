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

    private static $typeCheckers = [
        'boolean' => ['checker' => 'is_bool', 'resolver' => 'toScalar'],
        'integer' => ['checker' => 'is_int', 'resolver' => 'toScalar'],
        'number' => ['checker' => 'is_numeric', 'resolver' => 'toScalar'],
        'string' => ['checker' => 'is_string', 'resolver' => 'toScalar'],
        'array' => ['checker' => 'is_array', 'resolver' => 'toArray']
    ];

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
                    case 'array': case 'boolean': case 'integer': case 'number': case 'string':
                        if (!$this->validateScalar($input, $schema['type'])) {
                            throw new InvalidDataTypeException(
                                $this->renderInvalidDataTypeException($schema['type'], gettype($input))
                            );
                        }
                        break;
                    case 'null':
                        if (!is_null($input)) {
                            throw new InvalidDataTypeException(
                                $this->renderInvalidDataTypeException('null', gettype($input))
                            );
                        }
                        break;
                }
            }

            parent::__construct($input, null === $schema ? $this->getDefaultSchema() : $schema, $regulator);
        } catch (Exception $exception) {
            throw new InvalidDataTypeException($exception->getMessage(), $exception->getCode(), $exception);
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
     * @param string $expected
     * @param string $current
     *
     * @return string
     */
    protected function renderInvalidDataTypeException($expected, $current)
    {
        return sprintf('Incorrect data type: Expected %s, but got: %s in %s', $expected, $current, get_class($this));
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
            throw new InvalidDataTypeException($exception->getMessage(), $exception->getCode(), $exception);
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

    private function validateScalar($input, $type)
    {
        $typeFunctions = self::$typeCheckers[$type];

        return $typeFunctions['checker']($input) ||
        (
            ($input instanceof Dto) &&
            ($input->getStorageType() === self::STORAGE_TYPE_SCALAR) &&
            $typeFunctions['checker']($input->{$typeFunctions['resolver']}())
        );
    }
}
