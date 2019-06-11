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

namespace Shopgate\ConnectSdk\DTO\Catalog\Product;

use Shopgate\ConnectSdk\DTO\Base as DTOBase;

/**
 * @method Identifiers setLocationCode(string $mfgPartNum)
 * @method Identifiers setSku(string $sku)
 * @method Identifiers setOnHand(number $onHand)
 * @method Identifiers setOnReserve(number $onReserve)
 * @method Identifiers setSafetyStock(number $safetyStock)
 * @method Identifiers setAvailable(number $available)
 * @method Identifiers setVisible(number $visible)
 * @method Identifiers setBin(string $bin)
 * @method Identifiers setBinLocation(string $binLocation) *
 * @method Identifiers setExternalUpdateDte(string $externalUpdateDate) *
 */
class Inventory extends DTOBase
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
