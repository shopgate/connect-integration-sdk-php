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

namespace Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder;

use Shopgate\ConnectSdk\Dto\Order\Dto\Fulfillment;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Dto;

/**
 * @codeCoverageIgnore
 */
class Get extends FulfillmentOrder
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'orderNumber' => ['type' => 'string'],
            'externalCode' => ['type' => 'string'],
            'posTransactionId' => ['type' => 'string'],
            'cancellationReason' => ['type' => 'string'],
            'salesOrderNumber' => ['type' => 'string'],
            'locationCode' => ['type' => 'string'],
            'type' => ['type' => 'string'],
            'customerId' => ['type' => 'string'],
            'externalCustomerNumber' => ['type' => 'string'],
            'routeType' => ['type' => 'string'],
            'expedited' => ['type' => 'string'],
            'status' => ['type' => 'string'],
            'channel' => ['$ref' => Dto\Channel::class],
            'subTotal' => ['type' => 'number'],
            'taxAmount' => ['type' => 'number'],
            'tax2Amount' => ['type' => 'number'],
            'total' => ['type' => 'number'],
            'shippingTotal' => ['type' => 'number'],
            'localeCode' => ['type' => 'string'],
            'currencyCode' => ['type' => 'string'],
            'notes' => ['type' => 'string'],
            'specialInstructions' => ['type' => 'string'],
            'fulfillmentOrderAddress' => ['$ref' => Dto\FulfillmentOrderAddress::class],
            'fulfillments' => [
                'type' => 'array',
                'items' => ['$ref' => Fulfillment::class]
            ],
            'lineItems' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\LineItem::class]
            ],
            'history' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\HistoryItem::class]
            ],
            'orderSubmittedDate' => ['type' => 'string'],
            'acceptedDate' => ['type' => 'string'],
            'readyDate' => ['type' => 'string'],
            'completedDate' => ['type' => 'string'],
        ],
        'additionalProperties' => true
    ];
}
