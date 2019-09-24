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
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Attribute;

class SchemaRegulator extends JsonSchemaRegulator
{
    public function compileSchema($schema = null, $base_dir = '')
    {
        $newSchema = parent::compileSchema($schema, $base_dir);

        /**
         * We pass along the 'skipValidation' flag to all child definitions if the "parent" schema had this property set.
         */
        if (isset($schema['skipValidation'])) {
            if (isset($newSchema['properties']) && is_array($newSchema['properties'])) {
                foreach ($newSchema['properties'] as &$property) {
                    $property['skipValidation'] = true;
                }
            }
            if (empty($newSchema)) {
                $newSchema['skipValidation'] = true;
            }
        }

        return $newSchema;
    }

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
        $this->calledClass = $this->extractReference($schema, $key) ? : $this->calledClass;

        return parent::getFilteredValueForKey($value, $key, $schema);
    }

    /**
     * @param mixed $v
     * @param int   $index
     * @param array $schema
     *
     * @return mixed
     */
    public function getFilteredValueForIndex($v, $index, array $schema)
    {
        if ($this->isProductAttribute($v) && $this->extractReference($schema) === Properties::class) {
            $schema['items']['$ref'] = Attribute::class;
        }

        $this->calledClass = $this->extractReference($schema) ? : $this->calledClass;

        return parent::getFilteredValueForIndex($v, $index, $schema);
    }

    /**
     * Retrieves the correct reference of the called object
     *
     * @param array $schema
     * @param string|null $key - property name
     *
     * @return false|string
     */
    private function extractReference($schema, $key = null)
    {
        if (isset($schema['items']['$ref'])) {
            return $schema['items']['$ref'];
        }

        if (isset($schema['properties'][$key]['$ref'])) {
            return $schema['properties'][$key]['$ref'];
        }

        if (isset($schema['properties'][$key]['items']['$ref'])) {
            return $schema['properties'][$key]['items']['$ref'];
        }

        return false;
    }

    /**
     * Checks value type, specific check for Product->Property->Type = attribute
     *
     * @param mixed $value
     *
     * @return bool
     */
    private function isProductAttribute($value)
    {
        return isset($value['type']) && $value['type'] === Attribute::TYPE;
    }
}
