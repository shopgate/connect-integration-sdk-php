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

namespace Shopgate\ConnectSdk\Dto\Order\Order;

use Shopgate\ConnectSdk\Dto\Order\Order;

/**
 * @method Create setOrderNumber(string $orderNumber)
 * @method Create setExternalCode(string $externalCode)
 * @method Create setType(string $type)
 * @method Create setCustomerId(string $customerId)
 * @method Create setExternalCustomerNumber(string $externalCustomerNumber)
 * @method Create setStatus(string $status)
 * @method Create setExpedited(bool $expedited)
 * @method Create setLocaleCode(string $localCode)
 * @method Create setCurrencyCode(string $currencyCode)
 * @method Create setTaxExempt(bool $taxExempt)
 * @method Create setSpecialInstructions(Dto\SpecialInstructions $specialInstructions)
 * @method Create setData(Dto\Data $data)
 * @method Create setFulfillmentStatus(string $fulfillmentStatus)
 * @method Create setPrimaryBillToAddressSequenceIndex(int $primaryBillToAddressSequenceIndex)
 * @method Create setPrimaryShipToAddressSequenceIndex(int $primaryShipToAddressSequenceIndex)
 * @method Create setAddressSequences(Dto\Address[] $addressSequences)
 * @method Create setSubTotal(float $subTotal)
 * @method Create setDiscountAmount(float $discountAmount)
 * @method Create setPromoAmount(float $promoAmount)
 * @method Create setTaxAmount(float $taxAmount)
 * @method Create setTax2Amount(float $tax2Amount)
 * @method Create setShippingSubTotal(float $shippingSubTotal)
 * @method Create setShippingDiscountAmount(float $shippingDiscountAmount)
 * @method Create setShippingPromoAmount(float $shippingPromoAmount)
 * @method Create setShippingTotal(float $shippingTotal)
 * @method Create setTotal(float $total)
 * @method Create setDate(Dto\Data $date)
 * @method Create setSubmitDate(string $submitDate)
 * @method Create setAcceptDate(string $acceptDate)
 * @method Create setCompleteDate(string $completeDate)
 * @method Create setSourceDevice(string $sourceDevice)
 * @method Create setSourceIp(string $sourceIp)
 * @method Create setFulfillmentGroups(Dto\FulfillmentGroup[] $fulfillmentGroups)
 * @method Create setLineItems(Dto\LineItem[] $lineItems)
 * @method Create setHistory(Dto\HistoryItem[] $history)
 *
 * @codeCoverageIgnore
 */
class Create extends Order
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'orderNumber' => ['type' => 'string'],
            'externalCode' => ['type' => 'string'],
            'type' => ['type' => 'string'],
            'customerId' => ['type' => 'string'],
            'externalCustomerNumber' => ['type' => 'string'],
            'status' => ['type' => 'string'],
            'expedited' => ['type' => 'boolean'],
            'localeCode' => ['type' => 'string'],
            'currencyCode' => ['type' => 'string'],
            'taxExempt' => ['type' => 'boolean'],
            'specialInstructions' => ['$ref' => Dto\SpecialInstructions::class],
            'data' => ['$ref' => Dto\Data::class],
            'fulfillmentStatus' => ['type' => 'string'],
            'primaryBillToAddressSequenceIndex' => ['type' =>'number'],
            'primaryShipToAddressSequenceIndex' => ['type' => 'number'],
            'addressSequences' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Address::class]
            ],
            'subTotal' => ['type' =>'number'],
            'discountAmount' => ['type' =>'number'],
            'promoAmount' => ['type' =>'number'],
            'taxAmount' => ['type' =>'number'],
            'tax2Amount' => ['type' =>'number'],
            'shippingSubTotal' => ['type' =>'number'],
            'shippingDiscountAmount' => ['type' =>'number'],
            'shippingPromoAmount' => ['type' =>'number'],
            'shippingTotal' => ['type' =>'number'],
            'total' => ['type' =>'number'],
            'date' => ['type' => 'string'],
            'submitDate' => ['type' => 'string'],
            'acceptDate' => ['type' => 'string'],
            'completeDate' => ['type' => 'string'],
            'sourceDevice' => ['type' => 'string'],
            'sourceIp' => ['type' => 'string'],
            'fulfillmentGroups' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\FulfillmentGroup::class]
            ],
            'lineItems' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\LineItem::class]
            ],
            'history' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\HistoryItem::class]
            ]
        ],
        'additionalProperties' => true
    ];
}
