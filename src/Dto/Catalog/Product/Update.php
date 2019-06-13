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
 * Default class that handles validation for product Update payloads.
 *
 * @method Update setName(dto\Name $name)
 * @method Update setLongName(dto\LongName $longName)
 * @method Update setCategories(dto\Categories [] $categories)
 * @method Update setProperties(dto\Properties [] $properties)
 * @method Update setMedia(dto\Media $media)
 * @method Update setOptions(dto\Options [] $options)
 * @method Update setExtras(dto\Extras [] $extras)
 * @method Update setCode(string $code)
 * @method Update setParentProductCode(string $parentProductCode)
 * @method Update setCatalogCode(string $catalogCode)
 * @method Update setModelType(string $modelType)
 * @method Update setIdentifiers(dto\Identifiers $identifiers)
 * @method Update setPrice(dto\Price $price)
 * @method Update setFulfillmentMethods(string [] $fulfillmentMethods)
 * @method Update setUnit(string $unit)
 * @method Update setIsSerialized(boolean $isSerialized)
 * @method Update setStatus(string $status)
 * @method Update setStartDate(string $startDate)
 * @method Update setEndDate(string $endDate)
 * @method Update setFirstAvailableDate(string $firstAvailableDate)
 * @method Update setEolDate(string $eolDate)
 * @method Update setIsInventoryManaged(boolean $isInventoryManaged)
 * @method Update setInventoryTreatment(string $inventoryTreatment)
 * @method Update setShippingInformation(dto\ShippingInformation $shippingInformation)
 * @method Update setRating(number $rating)
 * @method Update setUrl(string $url)
 * @method Update setIsTaxed(boolean $isTaxed)
 * @method Update setTaxClass(string $taxClass)
 * @method Update setMinQty(number $minQty)
 * @method Update setMaxQty(number $maxQty)
 * @method Update setExternalUpdateDate(string $externalUpdateDate)
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
            'name'                => ['type' => 'object'],
            'longName'            => ['type' => 'object'],
            'categories'          => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object',
                ],
            ],
            'properties'          => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object',
                ],
            ],
            'media'               => ['type' => 'object'],
            'options'             => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object',
                ],
            ],
            'extras'              => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object',
                ],
            ],
            'code'                => ['type' => 'string'],
            'parentProductCode'   => ['type' => 'string'],
            'catalogCode'         => ['type' => 'string'],
            'modelType'           => ['type' => 'string'],
            'identifiers'         => ['type' => 'object'],
            'price'               => ['type' => 'object'],
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
            'shippingInformation' => ['type' => 'object'],
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
