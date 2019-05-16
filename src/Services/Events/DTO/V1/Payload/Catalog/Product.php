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
                    'standard',
                    'configurable',
                    'bundle',
                    'bundleItem',
                    'variant'
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
                    'active',
                    'inactive',
                    'deleted',
                    'scheduled'
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
                    'showOutOfStock',
                    'allowBackOrders',
                    'preOrder'
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
