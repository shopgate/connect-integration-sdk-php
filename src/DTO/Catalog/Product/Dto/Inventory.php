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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Inventory setLocationCode(string $mfgPartNum)
 * @method Inventory setSku(string $sku)
 * @method Inventory setOnHand(number $onHand)
 * @method Inventory setOnReserve(number $onReserve)
 * @method Inventory setSafetyStock(number $safetyStock)
 * @method Inventory setAvailable(number $available)
 * @method Inventory setVisible(number $visible)
 * @method Inventory setBin(string $bin)
 * @method Inventory setBinLocation(string $binLocation) *
 * @method Inventory setExternalUpdateDte(string $externalUpdateDate) *
 */
class Inventory extends Base
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'locationCode'       => ['type' => 'string'],
            'sku'                => ['type' => 'string'],
            'onHand'             => ['type' => 'number'],
            'onReserve'          => ['type' => 'number'],
            'safetyStock'        => ['type' => 'number'],
            'available'          => ['type' => 'number'],
            'visible'            => ['type' => 'number'],
            'bin'                => ['type' => 'string'],
            'binLocation'        => ['type' => 'string'],
            'externalUpdateDate' => ['type' => 'string'],
        ],
        'additionalProperties' => true,
    ];
}
