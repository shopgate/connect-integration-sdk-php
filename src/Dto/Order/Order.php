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
use Shopgate\ConnectSdk\Dto\Order\Order\Create;
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
 * @method string getUpdateDate()
 * @method string getCompleteDate()
 * @method string getSourceDevice()
 * @method string getSourceIp()
 * @method Dto\FulfillmentGroup[] getFulfillmentGroups()
 * @method Dto\LineItem[] getLineItems()
 *
 * @method $this setExternalCode(string $externalCode)
 * @method $this setType(string $type)
 * @method $this setCustomerId(string $customerId)
 * @method $this setExternalCustomerNumber(string $externalCustomerNumber)
 * @method $this setStatus(string $status)
 * @method $this setExpedited(bool $expedited)
 * @method $this setLocaleCode(string $localCode)
 * @method $this setCurrencyCode(string $currencyCode)
 * @method $this setTaxExempt(bool $taxExempt)
 * @method $this setNotes(string $notes)
 * @method $this setSpecialInstructions(Dto\SpecialInstructions $specialInstructions)
 * @method $this setData(Dto\Data $data)
 * @method $this setFulfillmentStatus(string $fulfillmentStatus)
 * @method $this setPrimaryBillToAddressSequenceIndex(int $primaryBillToAddressSequenceIndex)
 * @method $this setPrimaryShipToAddressSequenceIndex(int $primaryShipToAddressSequenceIndex)
 * @method $this setAddressSequences(Dto\Address[] $addressSequences)
 * @method $this setSubTotal(float $subTotal)
 * @method $this setDiscountAmount(float $discountAmount)
 * @method $this setPromoAmount(float $promoAmount)
 * @method $this setTaxAmount(float $taxAmount)
 * @method $this setTax2Amount(float $tax2Amount)
 * @method $this setShippingSubTotal(float $shippingSubTotal)
 * @method $this setShippingDiscountAmount(float $shippingDiscountAmount)
 * @method $this setShippingPromoAmount(float $shippingPromoAmount)
 * @method $this setShippingTotal(float $shippingTotal)
 * @method $this setTotal(float $total)
 * @method $this setDate(string $date)
 * @method $this setSubmitDate(string $submitDate)
 * @method $this setCompleteDate(string $completeDate)
 * @method $this setSourceDevice(string $sourceDevice)
 * @method $this setSourceIp(string $sourceIp)
 * @method $this setFulfillmentGroups(Dto\FulfillmentGroup[] $fulfillmentGroups)
 * @method $this setLineItems(Dto\LineItem[] $lineItems)
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
    const SOURCE_DEVICE_DESKTOP = 'desktop';
    const SOURCE_DEVICE_MOBILE = 'mobile';
    const SOURCE_DEVICE_APP = 'app';
    const SOURCE_DEVICE_OTHER = 'other';
}
