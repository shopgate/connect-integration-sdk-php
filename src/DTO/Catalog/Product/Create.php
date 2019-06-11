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

namespace Shopgate\ConnectSdk\DTO\Catalog\Product;

use Shopgate\ConnectSdk\DTO\Base as DTOBase;

/**
 * Default class that handles validation for product Create payloads.
 *
 * @method Create setName(Name $name)
 * @method Create setLongName(LongName $longName)
 * @method Create setCategories(CategoryMapping [] $categories)
 * @method Create setProperties(Property [] $properties)
 * @method Create setMedia(LocalizationMedia $media)
 * @method Create setOptions(Option [] $options)
 * @method Create setExtras(Extra [] $extras)
 * @method Create setCode(string $code)
 * @method Create setParentProductCode(string $parentProductCode)
 * @method Create setCatalogCode(string $catalogCode)
 * @method Create setModelType(string $modelType)
 * @method Create setIdentifiers(Identifiers $identifiers)
 * @method Create setPrice(Price $price)
 * @method Create setFulfillmentMethods(string [] $fulfillmentMethods)
 * @method Create setUnit(string $unit)
 * @method Create setIsSerialized(boolean $isSerialized)
 * @method Create setStatus(string $status)
 * @method Create setStartDate(string $startDate)
 * @method Create setEndDate(string $endDate)
 * @method Create setFirstAvailableDate(string $firstAvailableDate)
 * @method Create setEolDate(string $eolDate)
 * @method Create setIsInventoryManaged(boolean $isInventoryManaged)
 * @method Create setInventoryTreatment(string $inventoryTreatment)
 * @method Create setShippingInformation(ShippingInformation $shippingInformation)
 * @method Create setRating(number $rating)
 * @method Create setUrl(string $url)
 * @method Create setIsTaxed(boolean $isTaxed)
 * @method Create setTaxClass(string $taxClass)
 * @method Create setMinQty(number $minQty)
 * @method Create setMaxQty(number $maxQty)
 * @method Create setExternalUpdateDate(string $externalUpdateDate)
 */
class Create extends DTOBase
{
    const MODEL_TYPE_STANDARD     = 'standard';
    const MODEL_TYPE_CONFIGURABLE = 'configurable';
    const MODEL_TYPE_BUNDLE       = 'bundle';
    const MODEL_TYPE_BUNDLE_ITEM  = 'bundleItem';
    const MODEL_TYPE_VARIANT      = 'variant';
    const STATUS_ACTIVE    = 'active';
    const STATUS_INACTIVE  = 'inactive';
    const STATUS_DELETED   = 'deleted';
    const STATUS_SCHEDULED = 'scheduled';
    const INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK = 'showOutOfStock';
    const INVENTORY_TREATMENT_ALLOW_BACK_ORDERS = 'allowBackOrders';
    const INVENTORY_TREATMENT_PRE_ORDER         = 'preOrder';
    const FULFILLMENT_METHOD_SIMPLE_PICKUP_IN_STORE = 'simplePickUpInStore';
    const FULFILLMENT_METHOD_DIRECT_SHIP            = 'directShip';

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
