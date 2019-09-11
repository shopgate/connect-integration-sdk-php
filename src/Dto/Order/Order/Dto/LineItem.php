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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method LineItem setCode(string $code)
 * @method LineItem setQuantity(int $quantity)
 * @method LineItem setFulfillmentMethod(string $fulfillmentMethod)
 * @method LineItem setFulfillmentLocationCode(string $fulfillmentLocationCode)
 * @method LineItem setShipToAddressSequenceIndex(int $shipToAddressSequenceIndex)
 * @method LineItem setProduct(LineItem\Product $product)
 * @method LineItem setCurrencyCode(string $currencyCode)
 * @method LineItem setShippingAmount(float $shippingAmount)
 * @method LineItem setTaxAmount(float $taxAmount)
 * @method LineItem setTax2Amount(float $tax2Amount)
 * @method LineItem setTaxExempt(bool $taxExempt)
 * @method LineItem setDiscountAmount(float $discountAmount)
 * @method LineItem setPromoAmount(float $promoAmount)
 * @method LineItem setOverrideAmount(float $overrideAmount)
 * @method LineItem setExtendedPrice(float $extendedPrice)
 * @method LineItem setPrice(string $price)
 * @method string getCode()
 * @method int getQuantity()
 * @method string getFulfillmentMethod()
 * @method string getFulfillmentLocationCode()
 * @method int getShipToAddressSequenceIndex()
 * @method LineItem\Product getProduct()
 * @method string getCurrencyCode()
 * @method float getShippingAmount()
 * @method float getTaxAmount()
 * @method float getTax2Amount()
 * @method float getTaxExempt()
 * @method float getDiscountAmount()
 * @method float getPromoAmount()
 * @method float getOverrideAmount()
 * @method float getExtendedPrice()
 * @method float getPrice()
 *
 * @codeCoverageIgnore
 */
class LineItem extends Base
{
    const FULFILLMENT_METHOD_DIRECT_SHIP = 'directShip';
    const FULFILLMENT_METHOD_BOPIS = 'BOPIS';
    const FULFILLMENT_METHOD_ROPIS = 'ROPIS';

    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'code' => ['type' => 'string'],
            'quantity' => ['type' => 'number'],
            'fulfillmentMethod' => ['type' => 'string'],
            'fulfillmentLocationCode' => ['type' => 'string'],
            'shipToAddressSequenceIndex' => ['type' => 'number'],
            'product' => ['$ref' => LineItem\Product::class],
            'currencyCode' => ['type' => 'string'],
            'shippingAmount' => ['type' => 'number'],
            'taxAmount' => ['type' => 'number'],
            'tax2Amount' => ['type' => 'number'],
            'taxExempt' => ['type' => 'boolean'],
            'discountAmount' => ['type' => 'number'],
            'promoAmount' => ['type' => 'number'],
            'overrideAmount' => ['type' => 'number'],
            'extendedPrice' => ['type' => 'number'],
            'price' => ['type' => 'number']
        ],
        'additionalProperties' => true,
    ];
}
