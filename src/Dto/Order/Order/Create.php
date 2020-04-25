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
 * @inheritDoc
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
            'externalCode' => ['type' => 'string'],
            'type' => ['type' => 'string'],
            'customerId' => ['type' => 'string'],
            'externalCustomerNumber' => ['type' => 'string'],
            'status' => ['type' => 'string'],
            'expedited' => ['type' => 'boolean'],
            'localeCode' => ['type' => 'string'],
            'currencyCode' => ['type' => 'string'],
            'taxExempt' => ['type' => 'boolean'],
            'notes' => ['type' => 'string'],
            'specialInstructions' => ['$ref' => Dto\SpecialInstructions::class],
            'data' => ['$ref' => Dto\Data::class],
            'fulfillmentStatus' => ['type' => 'string'],
            'primaryBillToAddressSequenceIndex' => ['type' => 'number'],
            'primaryShipToAddressSequenceIndex' => ['type' => 'number'],
            'addressSequences' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Address::class]
            ],
            'subTotal' => ['type' => 'number'],
            'discountAmount' => ['type' => 'number'],
            'promoAmount' => ['type' => 'number'],
            'taxAmount' => ['type' => 'number'],
            'tax2Amount' => ['type' => 'number'],
            'shippingSubTotal' => ['type' => 'number'],
            'shippingDiscountAmount' => ['type' => 'number'],
            'shippingPromoAmount' => ['type' => 'number'],
            'shippingTotal' => ['type' => 'number'],
            'total' => ['type' => 'number'],
            'date' => ['type' => 'string'],
            'submitDate' => ['type' => 'string'],
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
                'items' => ['$ref' => Dto\History::class]
            ]
        ],
        'additionalProperties' => true
    ];
}
