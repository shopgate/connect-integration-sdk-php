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
 * @copyright 2019 Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Dto\Catalog\Product;

use Shopgate\ConnectSdk\Dto\Catalog\Product;

/**
 * @method string getCode()
 *
 * @inheritdoc
 */
class Create extends Product
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'name' => ['$ref' => Dto\Name::class],
            'longName' => ['$ref' => Dto\LongName::class],
            'shortDescription' => ['$ref' => Dto\ShortDescription::class],
            'longDescription' => ['$ref' => Dto\LongDescription::class],
            'categories' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Categories::class]
            ],
            'properties' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Properties::class]
            ],
            'media' => ['$ref' => Dto\MediaList::class],
            'options' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Options::class]
            ],
            'extras' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Extras::class]
            ],
            'code' => ['type' => 'string'],
            'parentProductCode' => ['type' => 'string'],
            'catalogCode' => ['type' => 'string'],
            'modelType' => ['type' => 'string'],
            'identifiers' => ['$ref' => Dto\Identifiers::class],
            'price' => ['$ref' => Dto\Price::class],
            'fulfillmentMethods' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'unit' => ['type' => 'string'],
            'unitValue' => ['type' => 'number'],
            'unitPriceRefUom' => ['type' => 'string'],
            'unitPriceRefValue' => ['type' => 'number'],
            'hasCatchWeight' => ['type' => 'boolean'],
            'isSerialized' => ['type' => 'boolean'],
            'status' => ['type' => 'string'],
            'startDate' => ['type' => 'string'],
            'endDate' => ['type' => 'string'],
            'firstAvailableDate' => ['type' => 'string'],
            'eolDate' => ['type' => 'string'],
            'isInventoryManaged' => ['type' => 'boolean'],
            'inventoryTreatment' => ['type' => 'string'],
            'shippingInformation' => ['$ref' => Dto\ShippingInformation::class],
            'rating' => ['type' => 'number'],
            'url' => ['type' => 'string'],
            'isTaxed' => ['type' => 'boolean'],
            'taxClass' => ['type' => 'string'],
            'minQty' => ['type' => 'number'],
            'maxQty' => ['type' => 'number'],
            'externalUpdateDate' => ['type' => 'string']
        ],
        'additionalProperties' => true,
    ];
}
