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

use Dto\Exceptions\InvalidKeyException;
use Dto\JsonSchemaRegulator;

class SchemaRegulator extends JsonSchemaRegulator
{
    /**
     * Rewritten to instantiate referenced objects instead of parent DTOs
     *
     * @param mixed  $value
     * @param string $key
     * @param array  $schema
     *
     * @return mixed
     * @throws InvalidKeyException
     */
    public function getFilteredValueForKey($value, $key, array $schema)
    {
        $schemaAccessor    = $this->schemaAccessor->factory($schema);
        $this->calledClass = $this->extractReference($key, $schema) ? : $this->calledClass;

        /** @noinspection PhpUndefinedMethodInspection */
        return new $this->calledClass(
            $value,
            $schemaAccessor->mergeMetaData($this->getSchemaAtKey($key, $schema)),
            $this
        );
    }

    /**
     * Retrieves the correct reference of the called object
     *
     * @param string $key - property name
     * @param array  $schema
     *
     * @return false|string
     */
    private function extractReference($key, array $schema)
    {
        if (isset($schema['properties'][$key]['$ref'])) {
            return $schema['properties'][$key]['$ref'];
        }

        if (isset($schema['properties'][$key]['items']['$ref'])) {
            return $schema['properties'][$key]['items']['$ref'];
        }

        return false;
    }
}
