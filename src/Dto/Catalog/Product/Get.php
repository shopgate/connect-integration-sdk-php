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

use Shopgate\ConnectSdk\Dto\Catalog\Product as ProductBase;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;

/**
 * @method Dto\MediaList\Media[] getMedia()
 * @method Dto\Properties[] getProperties()
 *
 * @inheritdoc
 */
class Get extends ProductBase
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'identifiers'         => ['$ref' => Dto\Identifiers::class, 'skipValidation' => true],
            'price'               => ['$ref' => Dto\Price::class, 'skipValidation' => true],
            'shippingInformation' => ['$ref' => Dto\ShippingInformation::class, 'skipValidation' => true],
            'categories'          => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Categories::class, 'skipValidation' => true]
            ],
            'properties'          => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Properties::class, 'skipValidation' => true]
            ],
            'media'               => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\MediaList\Media::class, 'skipValidation' => true]
            ],
            'inventories'         => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Inventory::class, 'skipValidation' => true]
            ],
            'options'             => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Options::class, 'skipValidation' => true]
            ],
            'extras'              => [
                'type'  => 'array',
                'items' => ['$ref' => Dto\Extras::class, 'skipValidation' => true]
            ],
        ],
        'additionalProperties' => true
    ];
}
