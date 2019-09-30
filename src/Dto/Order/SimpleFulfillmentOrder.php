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

namespace Shopgate\ConnectSdk\Dto\Order;

use Dto\RegulatorInterface;
use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Dto\FulfillmentOrderAddress;

/**
 * @method string getOrderNumber()
 * @method string getExternalCode()
 * @method string getPosTransactionId()
 * @method string getCancellationReason()
 * @method string getSalesOrderNumber()
 * @method string getLocationCode()
 * @method string getType()
 * @method string getCustomerId()
 * @method string getExternalCustomerNumber()
 * @method string getRouteType()
 * @method string getExpedited()
 * @method string getStatus()
 * @method float getSubTotal()
 * @method float getTaxAmount()
 * @method float getTax2Amount()
 * @method float getTotal()
 * @method float getShippingTotal()
 * @method string getLocaleCode()
 * @method string getCurrencyCode()
 * @method string getNotes()
 * @method string getSpecialInstructions()
 * @method string getSubmitDate()
 * @method string getAcceptDate()
 * @method string getReadyDate()
 * @method string getCompleteDate()
 * @method FulfillmentOrderAddress getFulfillmentOrderAddress()
 *
 * @codeCoverageIgnore
 */
class SimpleFulfillmentOrder extends Base
{
    const CANCELLATION_EXPIRED = 'expired';
    const CANCELLATION_DECLINED = 'declined';
    const CANCELLATION_NEW_PURCHASE = 'newPurchase';
    const TYPE_DIRECT_SHIP = 'directShip';
    const TYPE_BOPIS = 'BOPIS';
    const TYPE_ROPIS = 'ROPIS';
    const ROUTE_TYPE_STANDARD_DIRECT_SHIP = 'standardDirectShip';
    const ROUTE_TYPE_STANDARD_PICKUP = 'standardPickup';
    const ROUTE_TYPE_STANDARD_RESERVE = 'standardReserve';
    const STATUS_NEW = 'new';
    const STATUS_REQUESTED = 'requested';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_IN_PROGRESS = 'inProgress';
    const STATUS_PICKED = 'picked';
    const STATUS_PACKED = 'packed';
    const STATUS_READY = 'ready';
    const STATUS_HOLD = 'hold';
    const STATUS_CHECKED_IN = 'checkedIn';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_FULFILLED = 'fulfilled';

    /**
     * Rewritten to provide inheritance of payload structure
     *
     * @inheritDoc
     */
    public function __construct($input = null, $schema = null, RegulatorInterface $regulator = null)
    {
        $this->schema['properties']['fulfillmentOrderAddress'] = ['$ref' => FulfillmentOrderAddress::class];

        parent::__construct($input, $schema, $regulator);
    }

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
            'subTotal' => ['type' => 'number'],
            'taxAmount' => ['type' => 'number'],
            'tax2Amount' => ['type' => 'number'],
            'total' => ['type' => 'number'],
            'shippingTotal' => ['type' => 'number'],
            'localeCode' => ['type' => 'string'],
            'currencyCode' => ['type' => 'string'],
            'notes' => ['type' => 'string'],
            'specialInstructions' => ['type' => 'string'],
            'submitDate' => ['type' => 'string'],
            'updateDate' => ['type' => 'string'],
            'acceptDate' => ['type' => 'string'],
            'readyDate' => ['type' => 'string'],
            'completeDate' => ['type' => 'string'],
            //'fulfillmentOrderAddress' => ['type' => 'object']
        ],
        'additionalProperties' => true
    ];
}
