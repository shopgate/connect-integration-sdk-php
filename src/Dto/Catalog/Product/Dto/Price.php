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
 * @method Price setCost(number $cost)
 * @method Price setPrice(number $price)
 * @method Price setUnit(string $unit)
 * @method Price setSalePrice(number $salePrice)
 * @method Price setMsrp(number $msrp)
 * @method Price setMinPrice(number $minPrice)
 * @method Price setMaxPrice(number $maxPrice)
 * @method Price setVolumePricing(Price\VolumePricing[] $volumePricing)
 * @method Price setMapPricing(Price\MapPricing[] $mapPricing)
 * @method Price setCurrencyCode(string $currencyCode)
 *
 * @method number getCost()
 * @method number getPrice()
 * @method string getUnit()
 * @method number getSalePrice()
 * @method number getMsrp()
 * @method number getMinPrice()
 * @method number getMaxPrice()
 * @method Price\VolumePricing[] getVolumePricing()
 * @method Price\MapPricing[] getMapPricing()
 * @method string getCurrencyCode()
 */
class Price extends Base
{
    const CURRENCY_CODE_EUR = 'EUR';
    const CURRENCY_CODE_USD = 'USD';

    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'cost'          => ['type' => 'number'],
            'price'         => ['type' => 'number'],
            'unit'          => ['type' => 'string'],
            'salePrice'     => ['type' => 'number'],
            'msrp'          => ['type' => 'number'],
            'minPrice'      => ['type' => 'number'],
            'maxPrice'      => ['type' => 'number'],
            'volumePricing' => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'mapPricing'    => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ],
            'currencyCode'  => [
                'type' => 'string'
            ]
        ],
        'additionalProperties' => true
    ];
}
