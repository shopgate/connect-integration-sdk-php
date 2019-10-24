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

namespace Shopgate\ConnectSdk\Dto\Webhook\Webhook\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Event setCode(string $code)
 * @method string getCode() - event code to subscribe to, e.g. salesOrderAdded - when order is created
 *
 * @codeCoverageIgnore
 */
class Event extends Base
{
    const SALES_ORDER_ADDED = 'salesOrderAdded';
    const SALES_ORDER_STATUS_UPDATED = 'salesOrderStatusUpdated';
    const SALES_ORDER_FULFILLMENT_ADDED = 'salesOrderFulfillmentAdded';
    const FULFILL_ORDER_ADDED = 'fulfillmentOrderAdded';
    const FULFILL_ORDER_UPDATED = 'fulfillmentOrderUpdated';
    const FULFILL_ORDER_STATUS_UPDATED = 'fulfillmentOrderStatusUpdated';
    const ORDER_NOT_PICKED_UP = 'orderNotPickedUp';
    const INVENTORY_RESERVATION_DELETED = 'inventoryReservationDeleted';
    const INVENTORY_RESERVATION_SETTLED = 'inventoryReservationSettled';

    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'code' => ['type' => 'string']
        ]
    ];
}
