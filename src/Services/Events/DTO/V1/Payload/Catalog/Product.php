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

namespace Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog;

use Shopgate\ConnectSdk\Services\Events\DTO\Base as DTOBase;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Category;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Extra;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Identifiers;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Media;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Option;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Price;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\Property;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product\ShippingInformation;

/**
 * Default class that handles validation for product Update/Create payloads.
 * If there is a need to differentiate, one can create a class Update.php, etc. and extend this one
 *
 * @method Product setCode(string $code)
 * @method Product setParentProductCode(string $parentProductCode)
 * @method Product setName(string $name)
 * @method Product setLongName(string $longName)
 * @method Product setCatalogCode(string $catalogCode)
 * @method Product setModelType(string $modelType)
 * @method Product setCategories(Category[] $categories)
 * @method Product setIdentifiers(Identifiers $identifiers)
 * @method Product setPrice(Price $price)
 * @method Product setFulfillmentMethods(string[] $fulfillmentMethods)
 * @method Product setUnit(string $unit)
 * @method Product setIsSerialized(boolean $isSerialized)
 * @method Product setStatus(string $status)
 * @method Product setStartDate(string $startDate)
 * @method Product setEndDate(string $endDate)
 * @method Product setFirstAvailableDate(string $firstAvailableDate)
 * @method Product setEolDate(string $eolDate)
 * @method Product setIsInventoryManaged(boolean $isInventoryManaged)
 * @method Product setInventoryTreatment(string $inventoryTreatment)
 * @method Product setProperties(Property[] $properties)
 * @method Product setShippingInformation(ShippingInformation $shippingInformation)
 * @method Product setRating(number $rating)
 * @method Product setUrl(string $url)
 * @method Product setIsTaxed(boolean $isTaxed)
 * @method Product setTaxClass(string $taxClass)
 * @method Product setMedia(Media[] $media)
 * @method Product setMinQty(number $minQty)
 * @method Product setMaxQty(number $maxQty)
 * @method Product setShortDescription(string $shortDescription)
 * @method Product setLongDescription(string $longDescription)
 * @method Product setOptions(Option[] $options)
 * @method Product setExtras(Extra[] $extras)
 * @method Product setExternalUpdateDate(string $externalUpdateDate)
 */
class Product extends DTOBase
{
    const MODEL_TYPE_STANDARD = 'standard';
    const MODEL_TYPE_CONFIGURABLE = 'configurable';
    const MODEL_TYPE_BUNDLE = 'bundle';
    const MODEL_TYPE_BUNDLE_ITEM = 'bundleItem';
    const MODEL_TYPE_VARIANT = 'variant';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED = 'deleted';
    const STATUS_SCHEDULED = 'scheduled';

    const INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK = 'showOutOfStock';
    const INVENTORY_TREATMENT_ALLOW_BACK_ORDERS = 'allowBackOrders';
    const INVENTORY_TREATMENT_PRE_ORDER = 'preOrder';

    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'                => ['type' => 'string'],
            'parentProductCode'   => ['type' => 'string'],
            'name'                => ['type' => 'string'],
            'longName'            => ['type' => 'string'],
            'catalogCode'         => ['type' => 'string'],
            'modelType'           => [
                'type' => 'string',
                'enum' => [
                    self::MODEL_TYPE_STANDARD,
                    self::MODEL_TYPE_CONFIGURABLE,
                    self::MODEL_TYPE_BUNDLE,
                    self::MODEL_TYPE_BUNDLE_ITEM,
                    self::MODEL_TYPE_VARIANT
                ]
            ],
            'categories'          => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'identifiers'         => ['type' => 'object'],
            'price'               => ['type' => 'object'],
            'fulfillmentMethods'  => [
                'type'  => 'array',
                'items' => [
                    'type' => 'string'
                ]
            ],
            'unit'                => ['type' => 'string'],
            'isSerialized'        => ['type' => 'boolean'],
            'status'              => [
                'type' => 'string',
                'enum' => [
                    self::STATUS_ACTIVE,
                    self::STATUS_INACTIVE,
                    self::STATUS_DELETED,
                    self::STATUS_SCHEDULED
                ]
            ],
            'startDate'           => ['type' => 'string'],
            'endDate'             => ['type' => 'string'],
            'firstAvailableDate'  => ['type' => 'string'],
            'eolDate'             => ['type' => 'string'],
            'isInventoryManaged'  => ['type' => 'boolean'],
            'inventoryTreatment'  => [
                'type' => 'string',
                'enum' => [
                    self::INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK,
                    self::INVENTORY_TREATMENT_ALLOW_BACK_ORDERS,
                    self::INVENTORY_TREATMENT_PRE_ORDER
                ]
            ],
            'properties'          => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'shippingInformation' => ['type' => 'object'],
            'rating'              => ['type' => 'number'],
            'url'                 => ['type' => 'string'],
            'isTaxed'             => ['type' => 'boolean'],
            'taxClass'            => ['type' => 'string'],
            'media'               => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'minQty'              => ['type' => 'number'],
            'maxQty'              => ['type' => 'number'],
            'shortDescription'    => ['type' => 'string'],
            'longDescription'     => ['type' => 'string'],
            'options'             => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'extras'              => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'externalUpdateDate'  => ['type' => 'string']
        ],
        'additionalProperties' => true
    ];
}
