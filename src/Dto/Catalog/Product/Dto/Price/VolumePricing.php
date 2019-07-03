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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method VolumePricing setMinQty(float $minQty)
 * @method VolumePricing setMaxQty(float $maxQty)
 * @method VolumePricing setPrice(float $price)
 * @method VolumePricing setSalePrice(float $salePrice)
 * @method VolumePricing setUnit(string $unit)
 * @method VolumePricing setPriceType(string $priceType)
 *
 * @method float getMinQty()
 * @method float getMaxQty()
 * @method float getPrice()
 * @method float getSalePrice()
 * @method string getUnit()
 * @method string getPriceType()
 */
class VolumePricing extends Base
{
    const PRICE_TYPE_FIXED    = 'fixed';
    const PRICE_TYPE_RELATIVE = 'relative';

    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'minQty'    => ['type' => 'number'],
            'maxQty'    => ['type' => 'number'],
            'price'     => ['type' => 'number'],
            'salePrice' => ['type' => 'number'],
            'unit'      => ['type' => 'string'],
            'priceType' => ['type' => 'string']
        ],
        'additionalProperties' => true
    ];
}
