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

namespace Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Dto;

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Dto\LineItem\Product;

/**
 * @method LineItem setId(string $id)
 * @method LineItem setSalesOrderLineItemCode(string $status)
 * @method LineItem setSku(string $sku)
 * @method LineItem setQuantity(float $quantity)
 * @method LineItem setStatus(string $status)
 * @method LineItem setCurrencyCode(string $currencyCode)
 * @method LineItem setPrice(float $price)
 * @method LineItem setShippingAmount(float $shippingAmount)
 * @method LineItem setTaxAmount(float $taxAmount)
 * @method LineItem setTax2Amount(float $tax2Amount)
 * @method LineItem setTaxExempt(float $taxExempt)
 * @method LineItem setDiscountAmount(float $discountAmount)
 * @method LineItem setPromoAmount(float $promoAmount)
 * @method LineItem setOverrideAmount(float $overrideAmount)
 * @method LineItem setExtendedPrice(string $extendedPrice)
 * @method LineItem setProduct(Product[] $extendedPrice)
 *
 * @method string getId()
 * @method string getSalesOrderLineItemCode()
 * @method string getSku()
 * @method int getQuantity()
 * @method string getStatus()
 * @method string getCurrencyCode()
 * @method float getPrice()
 * @method float getShippingAmount()
 * @method float getTaxAmount()
 * @method float getTax2Amount()
 * @method boolean getTaxExempt()
 * @method float getDiscountAmount()
 * @method float getPromoAmount()
 * @method float getOverrideAmount()
 * @method float getExtendedPrice()
 * @method Product getProduct()
 *
 * @codeCoverageIgnore
 */
class LineItem extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'string'],
            'salesOrderLineItemCode' => ['type' => 'string'],
            'sku' => ['type' => 'string'],
            'quantity' => ['type' => 'number'],
            'status' => ['type' => 'string'],
            'currencyCode' => ['type' => 'string'],
            'price' => ['type' => 'number'],
            'shippingAmount' => ['type' => 'number'],
            'taxAmount' => ['type' => 'number'],
            'tax2Amount' => ['type' => 'number'],
            'taxExempt' => ['type' => 'boolean'],
            'discountAmount' => ['type' => 'number'],
            'promoAmount' => ['type' => 'number'],
            'overrideAmount' => ['type' => 'number'],
            'extendedPrice' => ['type' => 'number'],
            'product' => ['$ref' => Product::class],
        ],
        'additionalProperties' => true,
    ];
}
