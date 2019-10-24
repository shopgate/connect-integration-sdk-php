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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;

use Dto\RegulatorInterface;
use Exception;
use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

class LocalizationArray extends Base
{
    protected $schema = [
        'anyOf' => [
            [
                'type' => 'array',
                'items' => [
                    'type' => 'string'
                ]
            ],
            [
                'type' => 'object',
                'additionalProperties' => true,
            ]
        ]
    ];

    /**
     * @inheritDoc
     */
    public function __construct($input = [], $schema = null, RegulatorInterface $regulator = null)
    {
        /**
         * This is to allow initializing this class without parameters,
         * because normally the translations will be added via add() method, not the constructor parameter
         */
        parent::__construct($input, null === $schema ? $this->getDefaultSchema() : $schema, $regulator);
        if ($input === []) {
            $this->storage_type = 'object';
        }
    }

    /**
     * @param string $locale
     * @param string[] $array
     *
     * @return $this
     *
     * @throws InvalidDataTypeException
     */
    public function add($locale, $array)
    {
        /**
         * We want to make sure when the dto was initialized as scalar type to hydrate the new value correctly
         * so the storage_type will change to object
         */
        if ($this->getStorageType() === self::STORAGE_TYPE_SCALAR) {
            try {
                $this->hydrate([$locale => $array]);
            } catch (Exception $ex) {
            }
            return $this;
        }

        $this->set((string)$locale, (array)$array);

        return $this;
    }
}
