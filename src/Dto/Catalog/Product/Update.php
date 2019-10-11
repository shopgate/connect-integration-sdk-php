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
 * @method Update setName(Dto\Name $name)
 * @method Update setLongName(Dto\LongName $longName)
 * @method Update setShortDescription(Dto\ShortDescription $name)
 * @method Update setLongDescription(Dto\LongDescription $longName)
 * @method Update setCategories(Dto\Categories[] $categories)
 * @method Update setProperties(Dto\Properties[] $properties)
 * @method Update setMedia(Dto\MediaList $media)
 * @method Update setOptions(Dto\Options[] $options)
 * @method Update setExtras(Dto\Extras[] $extras)
 * @method Update setCode(string $code)
 * @method Update setParentProductCode(string $parentProductCode)
 * @method Update setCatalogCode(string $catalogCode)
 * @method Update setModelType(string $modelType)
 * @method Update setIdentifiers(Dto\Identifiers $identifiers)
 * @method Update setPrice(Dto\Price $price)
 * @method Update setFulfillmentMethods(string[] $fulfillmentMethods)
 * @method Update setUnit(string $unit)
 * @method Update setIsSerialized(boolean $isSerialized)
 * @method Update setStatus(string $status)
 * @method Update setStartDate(string $startDate)
 * @method Update setEndDate(string $endDate)
 * @method Update setFirstAvailableDate(string $firstAvailableDate)
 * @method Update setEolDate(string $eolDate)
 * @method Update setIsInventoryManaged(boolean $isInventoryManaged)
 * @method Update setInventoryTreatment(string $inventoryTreatment)
 * @method Update setShippingInformation(Dto\ShippingInformation $shippingInformation)
 * @method Update setRating(float $rating)
 * @method Update setUrl(string $url)
 * @method Update setIsTaxed(boolean $isTaxed)
 * @method Update setTaxClass(string $taxClass)
 * @method Update setMinQty(float $minQty)
 * @method Update setMaxQty(float $maxQty)
 * @method Update setExternalUpdateDate(string $externalUpdateDate)
 *
 * @inheritdoc
 */
class Update extends Product
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'name'                => ['$ref' => Dto\Name::class],
            'longName'            => ['$ref' => Dto\LongName::class],
            'shortDescription'    => ['$ref' => Dto\ShortDescription::class],
            'longDescription'     => ['$ref' => Dto\LongDescription::class],
            'categories'          => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Categories::class]
            ],
            'properties'          => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Properties::class]
            ],
            'media'               => ['$ref' => Dto\MediaList::class],
            'options'             => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Options::class]
            ],
            'extras'              => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Extras::class]
            ],
            'code'                => ['type' => 'string'],
            'parentProductCode'   => ['type' => 'string'],
            'catalogCode'         => ['type' => 'string'],
            'modelType'           => ['type' => 'string'],
            'identifiers'         => ['$ref' => Dto\Identifiers::class],
            'price'               => ['$ref' => Dto\Price::class],
            'fulfillmentMethods'  => [
                'type'  => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'unit'                => ['type' => 'string'],
            'isSerialized'        => ['type' => 'boolean'],
            'status'              => ['type' => 'string'],
            'startDate'           => ['type' => 'string'],
            'endDate'             => ['type' => 'string'],
            'firstAvailableDate'  => ['type' => 'string'],
            'eolDate'             => ['type' => 'string'],
            'isInventoryManaged'  => ['type' => 'boolean'],
            'inventoryTreatment'  => ['type' => 'string'],
            'shippingInformation' => ['$ref' => Dto\ShippingInformation::class],
            'rating'              => ['type' => 'number'],
            'url'                 => ['type' => 'string'],
            'isTaxed'             => ['type' => 'boolean'],
            'taxClass'            => ['type' => 'string'],
            'minQty'              => ['type' => 'number'],
            'maxQty'              => ['type' => 'number'],
            'externalUpdateDate'  => ['type' => 'string'],
        ],
        'additionalProperties' => true,
    ];
}
