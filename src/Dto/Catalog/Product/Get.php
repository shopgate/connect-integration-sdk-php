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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product;

use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;

/**
 * Dto for product response.
 *
 * @method string getCode()
 * @method string getParentProductCode()
 * @method string getCatalogCode()
 * @method string getModelType()
 * @method Dto\Identifiers getIdentifiers()
 * @method Dto\Price getPrice()
 * @method string[] getFulfillmentMethods()
 * @method string getUnit()
 * @method boolean getIsSerialized()
 * @method string getStatus()
 * @method string getStartDate()
 * @method string getEndDate()
 * @method string getFirstAvailableDate()
 * @method string getEolDate()
 * @method boolean getIsInventoryManaged()
 * @method string getInventoryTreatment()
 * @method Dto\ShippingInformation getShippingInformation()
 * @method float getRating()
 * @method string getUrl()
 * @method boolean getTaxClass()
 * @method float getMinQty()
 * @method float getMaxQty()
 * @method string getName()
 * @method string getLongName()
 * @method Dto\Categories[] getCategories()
 * @method Dto\Properties[] getProperties()
 * @method Dto\MediaList\Media[] getMedia()
 * @method Dto\Inventory[] getInventory()
 * @method Dto\Options[] getOptions()
 * @method Dto\Extras[] getExtras()
 *
 * @codeCoverageIgnore
 */
class Get extends Product
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'identifiers'         => ['$ref' => Dto\Identifiers::class],
            'price'               => ['$ref' => Dto\Price::class],
            'shippingInformation' => ['$ref' => Dto\ShippingInformation::class],
            'categories'          => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Categories::class]
            ],
            'properties'          => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Properties::class]
            ],
            'media'               => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\MediaList\Media::class]
            ],
            'inventory'         => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Inventory::class]
            ],
            'options'             => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Options::class]
            ],
            'extras'              => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Extras::class]
            ],
        ],
        'additionalProperties' => true
    ];
}
