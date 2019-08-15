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

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Order\Order\Dto;

/**
 * @method string getOrderNumber()
 * @method string getExternalCode()
 * @method string getType()
 * @method string getCustomerId()
 * @method string getExternalCustomerNumber()
 * @method string getStatus()
 * @method bool getExpedited()
 * @method string getLocaleCode()
 * @method string getCurrencyCode()
 * @method bool getTaxExempt()
 * @method string getNotes()
 * @method Dto\SpecialInstructions getSpecialInstructions()
 * @method Dto\Data getData()
 * @method string getFulfillmentStatus()
 * @method int getPrimaryBillToAddressSequenceIndex()
 * @method int getPrimaryShipToAddressSequenceIndex()
 * @method Dto\Address[] getAddressSequences()
 * @method float getSubTotal()
 * @method float getDiscountAmount()
 * @method float getPromoAmount()
 * @method float getTaxAmount()
 * @method float getTax2Amount()
 * @method float getShippingSubTotal()
 * @method float getShippingDiscountAmount()
 * @method float getShippingPromoAmount()
 * @method float getShippingTotal()
 * @method float getTotal()
 * @method string getDate()
 * @method string getSubmitDate()
 * @method string getAcceptDate()
 * @method string getCompleteDate()
 * @method string getSourceDevice()
 * @method string getSourceIp()
 * @method Dto\FulfillmentGroup[] getFulfillmentGroups()
 * @method Dto\LineItem[] getLineItems()
 * @method Dto\HistoryItem[] getHistory()
 */
class Order extends Base
{
    const TYPE_STANDARD = 'standard';
    const STATUS_NEW = 'new';
    const STATUS_OPEN = 'open';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';
    const STATUS_READY = 'ready';
    const STATUS_FULFILLED = 'fulfilled';
    const STATUS_COMPLETED = 'completed';
    const FULFILLMENT_STATUS_OPEN = 'open';
    const FULFILLMENT_STATUS_IN_PROGRESS = 'inProgress';
    const FULFILLMENT_STATUS_FULFILLED = 'fulfilled';
    const SOURCE_DEVICE_DESKTOP = 'desktop';
    const SOURCE_DEVICE_MOBILE = 'mobile';
    const SOURCE_DEVICE_APP = 'app';
    const SOURCE_DEVICE_OTHER = 'other';
    const LINE_ITEM_FULFILLMENT_METHOD_DIRECT_SHIP = 'directShip';
    const LINE_ITEM_FULFILLMENT_METHOD_BOPIS = 'BOPIS';
    const LINE_ITEM_FULFILLMENT_METHOD_ROPIS = 'ROPIS';
    const ADDRESS_TYPE_PICKUP = 'pickup';
    const ADDRESS_TYPE_SHIPPING = 'shipping';
    const ADDRESS_TYPE_BILLING = 'billing';
}
