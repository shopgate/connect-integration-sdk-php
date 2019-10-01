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
 * @method Create setName(Dto\Name $name)
 * @method Create setLongName(Dto\LongName $longName)
 * @method Create setShortDescription(Dto\ShortDescription $name)
 * @method Create setLongDescription(Dto\LongDescription $longName)
 * @method Create setCategories(Dto\Categories[] $categories)
 * @method Create setProperties(Dto\Properties[] $properties)
 * @method Create setMedia(Dto\MediaList $media)
 * @method Create setOptions(Dto\Options[] $options)
 * @method Create setExtras(Dto\Extras[] $extras)
 * @method Create setCode(string $code)
 * @method Create setParentProductCode(string $parentProductCode)
 * @method Create setCatalogCode(string $catalogCode)
 * @method Create setModelType(string $modelType)
 * @method Create setIdentifiers(Dto\Identifiers $identifiers)
 * @method Create setPrice(Dto\Price $price)
 * @method Create setFulfillmentMethods(string[] $fulfillmentMethods)
 * @method Create setUnit(string $unit)
 * @method Create setIsSerialized(boolean $isSerialized)
 * @method Create setStatus(string $status)
 * @method Create setStartDate(string $startDate)
 * @method Create setEndDate(string $endDate)
 * @method Create setFirstAvailableDate(string $firstAvailableDate)
 * @method Create setEolDate(string $eolDate)
 * @method Create setIsInventoryManaged(boolean $isInventoryManaged)
 * @method Create setInventoryTreatment(string $inventoryTreatment)
 * @method Create setShippingInformation(Dto\ShippingInformation $shippingInformation)
 * @method Create setRating(float $rating)
 * @method Create setUrl(string $url)
 * @method Create setIsTaxed(boolean $isTaxed)
 * @method Create setTaxClass(string $taxClass)
 * @method Create setMinQty(float $minQty)
 * @method Create setMaxQty(float $maxQty)
 * @method Create setExternalUpdateDate(string $externalUpdateDate)
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
            'externalUpdateDate' => ['type' => 'string'],
            'sequenceId' => ['type' => 'number']
        ],
        'additionalProperties' => true,
    ];
}
