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
     */
    public function getFilteredValueForKey($value, $key, array $schema)
    {
        $this->calledClass = $this->extractReference($key, $schema) ? : $this->calledClass;

        return parent::getFilteredValueForKey($value, $key, $schema);
    }

    public function getFilteredValueForIndex($v, $index, array $schema) {
        $this->calledClass = $this->extractReference($index, $schema) ? : $this->calledClass;

        return parent::getFilteredValueForIndex($v, $index, $schema);
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

        if (isset($schema['items']['$ref'])) {
            return $schema['items']['$ref'];
        }

        return false;
    }
}
