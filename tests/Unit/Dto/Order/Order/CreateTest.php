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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\Order;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\Dto\Fulfillment;
use Shopgate\ConnectSdk\Dto\Order\Order\Create;
use Shopgate\ConnectSdk\Dto\Order\Order\Dto as OrderDto;
use Shopgate\ConnectSdk\Exception\Exception;

class CreateTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBasicProperties()
    {
        $entry = [
            'orderNumber' => 'testOrderNumber',
            'externalCode' => 'testExternalCode',
            'type' => 'standard',
            'customerId' => 'testCustomerId',
            'externalCustomerNumber' => 'testExternalCustomerNumber',
            'status' => 'new',
            'expedited' => false,
            'localeCode' => 'en-us',
            'currencyCode' => 'USD',
            'taxExempt' => true,
            'notes' => 'these are test notes',
            'fulfillmentStatus' => 'open',
            'primaryBillToAddressSequenceIndex' => 0,
            'primaryShipToAddressSequenceIndex' => 0,
            'addressSequences' => [
                [
                    'type' => 'billing',
                    'firstName' => 'Howard',
                    'middleName' => 'The',
                    'lastName' => 'Duck',
                    'company' => 'Shopgate',
                    'address1' => 'address one',
                    'address2' => 'address two',
                    'address3' => 'address three',
                    'address4' => 'address four',
                    'city' => 'Austin',
                    'region' => 'TX',
                    'postalCode' => '78610',
                    'country' => 'USA',
                    'phone' => '123-123-1234',
                    'fax' => '321-321-4321',
                    'mobile' => '789-789-7894',
                    'emailAddress' => 'howard@theduck.com'
                ]
            ],
            'subTotal' => 100,
            'discountAmount' => 0,
            'promoAmount' => 0,
            'taxAmount' => 0,
            'tax2Amount' => 0,
            'shippingSubTotal' => 5,
            'shippingDiscountAmount' => 0,
            'shippingPromoAmount' => 0,
            'shippingTotal' => 5,
            'total' => 105,
            'date' => 'today',
            'submitDate' => 'today',
            'sourceDevice' => 'mobile',
            'sourceIp' => '111.111.111.111',
            'fulfillmentGroups' => [
                [
                    'id' => 'test-fulfillment-group-id',
                    'fulfillmentMethod' => 'directShip',
                    'fulfillmentLocationCode' => 'test-store',
                    'shipToAddressSequenceIndex' => 0,
                    'fulfillments' => [
                        [
                            'id' => 'test-fulfillment-id',
                            'status' => 'open',
                            'carrier' => 'UPS',
                            'serviceLevel' => 'sameDay',
                            'tracking' => 'test-tracking-number',
                            'fulfillmentPackages' => [
                                [
                                    'id' => 347654,
                                    'status' => 'open',
                                    'serviceLevel' => 'twoDay',
                                    'fulfilledFromLocationCode' => 'test-warehouse-one',
                                    'weight' => 1.1,
                                    'dimensions' => 'dimensions string',
                                    'tracking' => 'test-tracking-code-two',
                                    'pickUpBy' => 'Johnny Bravo',
                                    'labelUrl' => 'test-label-url',
                                    'fulfillmentDate' => 'tomorrow',
                                    'packageItems' => [
                                        [
                                            'id' => 237765,
                                            'lineItemId' => 239984,
                                            'quantity' => 1
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'lineItems' => [
                [
                    'code' => 'test-line-item-one',
                    'quantity' => 1,
                    'fulfillmentMethod' => 'directShip',
                    'fulfillmentLocationCode' => 'warehouse-one',
                    'shipToAddressSequenceIndex' => 0,
                    'product' => [
                        'code' => 'product-one',
                        'name' => 'Product One',
                        'image' => 'image-url',
                        'price' => 100,
                        'currencyCode' => 'USD',
                        'options' => [
                            [
                                'code' => '146',
                                'name' => 'Color',
                                'value' => [
                                    'code' => '432',
                                    'name' => 'Red',
                                ],
                            ]
                        ]
                    ],
                    'currencyCode' => 'USD',
                    'shippingAmount' => 5,
                    'taxAmount' => 0,
                    'tax2Amount' => 0,
                    'taxExempt' => true,
                    'discountAmount' => 0,
                    'promoAmount' => 0,
                    'overrideAmount' => 0,
                    'extendedPrice' => 0,
                    'price' => 100
                ]
            ],
            'history' => [
                [
                    'id' => 123,
                    'eventName' => 'created',
                    'eventDetails' => 'test event details',
                    'eventNewValue' => ['test' => 'newValue'],
                    'eventOldValue' => 'test event old value',
                    'eventDateTime' => 'right now',
                    'eventUser' => 'Dana Scully'
                ]
            ]
        ];
        $get = new Create($entry);

        $this->assertEquals($entry['orderNumber'], $get->getOrderNumber());
        $this->assertEquals($entry['externalCode'], $get->getExternalCode());
        $this->assertEquals($entry['type'], $get->getType());
        $this->assertEquals($entry['customerId'], $get->getCustomerId());
        $this->assertEquals($entry['externalCustomerNumber'], $get->getExternalCustomerNumber());
        $this->assertEquals($entry['status'], $get->getStatus());
        $this->assertEquals($entry['expedited'], $get->getExpedited());
        $this->assertEquals($entry['localeCode'], $get->getLocaleCode());
        $this->assertEquals($entry['currencyCode'], $get->getCurrencyCode());
        $this->assertEquals($entry['taxExempt'], $get->getTaxExempt());
        $this->assertEquals($entry['notes'], $get->getNotes());
        $this->assertEquals($entry['fulfillmentStatus'], $get->getFulfillmentStatus());
        $this->assertEquals($entry['primaryBillToAddressSequenceIndex'], $get->getPrimaryBillToAddressSequenceIndex());
        $this->assertEquals($entry['primaryShipToAddressSequenceIndex'], $get->getPrimaryShipToAddressSequenceIndex());
        $actualAddress = $get->getAddressSequences()[0];
        $expectedAddress = $entry['addressSequences'][0];
        $this->assertInstanceOf(OrderDto\Address::class, $actualAddress);
        $this->assertEquals($expectedAddress['type'], $actualAddress->getType());
        $this->assertEquals($expectedAddress['firstName'], $actualAddress->getFirstName());
        $this->assertEquals($expectedAddress['middleName'], $actualAddress->getMiddleName());
        $this->assertEquals($expectedAddress['lastName'], $actualAddress->getLastName());
        $this->assertEquals($expectedAddress['company'], $actualAddress->getCompany());
        $this->assertEquals($expectedAddress['address1'], $actualAddress->getAddress1());
        $this->assertEquals($expectedAddress['address2'], $actualAddress->getAddress2());
        $this->assertEquals($expectedAddress['address3'], $actualAddress->getAddress3());
        $this->assertEquals($expectedAddress['address4'], $actualAddress->getAddress4());
        $this->assertEquals($expectedAddress['city'], $actualAddress->getCity());
        $this->assertEquals($expectedAddress['region'], $actualAddress->getRegion());
        $this->assertEquals($expectedAddress['postalCode'], $actualAddress->getPostalCode());
        $this->assertEquals($expectedAddress['country'], $actualAddress->getCountry());
        $this->assertEquals($expectedAddress['phone'], $actualAddress->getPhone());
        $this->assertEquals($expectedAddress['fax'], $actualAddress->getFax());
        $this->assertEquals($expectedAddress['mobile'], $actualAddress->getMobile());
        $this->assertEquals($expectedAddress['emailAddress'], $actualAddress->getEmailAddress());
        $this->assertEquals($entry['subTotal'], $get->getSubTotal());
        $this->assertEquals($entry['discountAmount'], $get->getDiscountAmount());
        $this->assertEquals($entry['promoAmount'], $get->getPromoAmount());
        $this->assertEquals($entry['taxAmount'], $get->getTaxAmount());
        $this->assertEquals($entry['tax2Amount'], $get->getTax2Amount());
        $this->assertEquals($entry['shippingSubTotal'], $get->getShippingTotal());
        $this->assertEquals($entry['shippingDiscountAmount'], $get->getShippingDiscountAmount());
        $this->assertEquals($entry['shippingPromoAmount'], $get->getShippingPromoAmount());
        $this->assertEquals($entry['shippingTotal'], $get->getShippingTotal());
        $this->assertEquals($entry['total'], $get->getTotal());
        $this->assertEquals($entry['date'], $get->getDate());
        $this->assertEquals($entry['submitDate'], $get->getSubmitDate());
        $this->assertEquals($entry['sourceDevice'], $get->getSourceDevice());
        $this->assertEquals($entry['sourceIp'], $get->getSourceIp());
        $actualFulfillmentGroup = $get->getFulfillmentGroups()[0];
        $expectedFulfillmentGroup = $entry['fulfillmentGroups'][0];
        $this->assertInstanceOf(OrderDto\FulfillmentGroup::class, $actualFulfillmentGroup);
        $this->assertEquals($expectedFulfillmentGroup['id'], $actualFulfillmentGroup->getId());
        $this->assertEquals(
            $expectedFulfillmentGroup['fulfillmentMethod'],
            $actualFulfillmentGroup->getFulfillmentMethod()
        );
        $this->assertEquals(
            $expectedFulfillmentGroup['fulfillmentLocationCode'],
            $actualFulfillmentGroup->getFulfillmentLocationCode()
        );
        $this->assertEquals(
            $expectedFulfillmentGroup['shipToAddressSequenceIndex'],
            $actualFulfillmentGroup->getShipToAddressSequenceIndex()
        );
        $actualFulfillment = $actualFulfillmentGroup->getFulfillments()[0];
        $expectFulfillment = $expectedFulfillmentGroup['fulfillments'][0];
        $this->assertInstanceOf(Fulfillment::class, $actualFulfillment);
        $this->assertEquals($expectFulfillment['id'], $actualFulfillment->getId());
        $this->assertEquals($expectFulfillment['status'], $actualFulfillment->getStatus());
        $this->assertEquals($expectFulfillment['carrier'], $actualFulfillment->getCarrier());
        $this->assertEquals($expectFulfillment['serviceLevel'], $actualFulfillment->getServiceLevel());
        $this->assertEquals($expectFulfillment['tracking'], $actualFulfillment->getTracking());
        $actualFulfillmentPackage = $actualFulfillment->getFulfillmentPackages()[0];
        $expectedFulfillmentPackage = $expectFulfillment['fulfillmentPackages'][0];
        $this->assertInstanceOf(
            Fulfillment\FulfillmentPackage::class,
            $actualFulfillmentPackage
        );
        $this->assertEquals($expectedFulfillmentPackage['id'], $actualFulfillmentPackage->getId());
        $this->assertEquals($expectedFulfillmentPackage['status'], $actualFulfillmentPackage->getStatus());
        $this->assertEquals($expectedFulfillmentPackage['serviceLevel'], $actualFulfillmentPackage->getServiceLevel());
        $this->assertEquals(
            $expectedFulfillmentPackage['fulfilledFromLocationCode'],
            $actualFulfillmentPackage->getFulfilledFromLocationCode()
        );
        $this->assertEquals($expectedFulfillmentPackage['weight'], $actualFulfillmentPackage->getWeight());
        $this->assertEquals($expectedFulfillmentPackage['dimensions'], $actualFulfillmentPackage->getDimensions());
        $this->assertEquals($expectedFulfillmentPackage['tracking'], $actualFulfillmentPackage->getTracking());
        $this->assertEquals($expectedFulfillmentPackage['pickUpBy'], $actualFulfillmentPackage->getPickUpBy());
        $this->assertEquals($expectedFulfillmentPackage['labelUrl'], $actualFulfillmentPackage->getLabelUrl());
        $this->assertEquals(
            $expectedFulfillmentPackage['fulfillmentDate'],
            $actualFulfillmentPackage->getFulfillmentDate()
        );
        $actualPackageItems = $actualFulfillmentPackage->getPackageItems()[0];
        $expectedPackageItems = $expectedFulfillmentPackage['packageItems'][0];
        $this->assertInstanceOf(
            Fulfillment\FulfillmentPackage\PackageItem::class,
            $actualPackageItems
        );
        $this->assertEquals($expectedPackageItems['id'], $actualPackageItems->getId());
        $this->assertEquals($expectedPackageItems['lineItemId'], $actualPackageItems->getLineItemId());
        $this->assertEquals($expectedPackageItems['quantity'], $actualPackageItems->getQuantity());
        $actualLineItem = $get->getLineItems()[0];
        $expectedLineItem = $entry['lineItems'][0];
        $this->assertInstanceOf(OrderDto\LineItem::class, $actualLineItem);
        $this->assertEquals($expectedLineItem['code'], $actualLineItem->getCode());
        $this->assertEquals($expectedLineItem['quantity'], $actualLineItem->getQuantity());
        $this->assertEquals($expectedLineItem['fulfillmentMethod'], $actualLineItem->getFulfillmentMethod());
        $this->assertEquals(
            $expectedLineItem['fulfillmentLocationCode'],
            $actualLineItem->getFulfillmentLocationCode()
        );
        $this->assertEquals(
            $expectedLineItem['shipToAddressSequenceIndex'],
            $actualLineItem->getShipToAddressSequenceIndex()
        );
        $this->assertEquals($expectedLineItem['currencyCode'], $actualLineItem->getCurrencyCode());
        $this->assertEquals($expectedLineItem['shippingAmount'], $actualLineItem->getShippingAmount());
        $this->assertEquals($expectedLineItem['taxAmount'], $actualLineItem->getTaxAmount());
        $this->assertEquals($expectedLineItem['tax2Amount'], $actualLineItem->getTax2Amount());
        $this->assertEquals($expectedLineItem['taxExempt'], $actualLineItem->getTaxExempt());
        $this->assertEquals($expectedLineItem['discountAmount'], $actualLineItem->getDiscountAmount());
        $this->assertEquals($expectedLineItem['promoAmount'], $actualLineItem->getPromoAmount());
        $this->assertEquals($expectedLineItem['overrideAmount'], $actualLineItem->getOverrideAmount());
        $this->assertEquals($expectedLineItem['extendedPrice'], $actualLineItem->getExtendedPrice());
        $this->assertEquals($expectedLineItem['price'], $actualLineItem->getPrice());

        $lineItemProduct = $actualLineItem->getProduct();
        $this->assertInstanceOf(OrderDto\LineItem\Product::class, $lineItemProduct);
        $this->assertEquals($expectedLineItem['product']['code'], $lineItemProduct->getCode());
        $this->assertEquals($expectedLineItem['product']['name'], $lineItemProduct->getName());
        $this->assertEquals($expectedLineItem['product']['image'], $lineItemProduct->getImage());
        $this->assertEquals($expectedLineItem['product']['price'], $lineItemProduct->getPrice());
        $this->assertEquals($expectedLineItem['product']['currencyCode'], $lineItemProduct->getCurrencyCode());

        $productOptions = $lineItemProduct->getOptions();
        $this->assertTrue(is_array($productOptions));
        $productOption = $productOptions[0];
        $this->assertEquals($expectedLineItem['product']['options'][0]['code'], $productOption->getCode());
        $this->assertEquals($expectedLineItem['product']['options'][0]['name'], $productOption->getName());
        $this->assertEquals($expectedLineItem['product']['options'][0]['value']['code'], $productOption->getValue()->getCode());
        $this->assertEquals($expectedLineItem['product']['options'][0]['value']['name'], $productOption->getValue()->getName());

        $actualHistoryItem = $get->getHistory()[0];
        $expectedHistoryItem = $entry['history'][0];
        $this->assertInstanceOf(OrderDto\HistoryItem::class, $actualHistoryItem);
        $this->assertEquals($expectedHistoryItem['id'], $actualHistoryItem->getId());
        $this->assertEquals($expectedHistoryItem['eventName'], $actualHistoryItem->getEventName());
        $this->assertEquals($expectedHistoryItem['eventDetails'], $actualHistoryItem->getEventDetails());
        $this->assertEquals($expectedHistoryItem['eventNewValue'], $actualHistoryItem->getEventNewValue()->toArray());
        $this->assertEquals($expectedHistoryItem['eventOldValue'], $actualHistoryItem->getEventOldValue());
        $this->assertEquals($expectedHistoryItem['eventDateTime'], $actualHistoryItem->getEventDateTime());
        $this->assertEquals($expectedHistoryItem['eventUser'], $actualHistoryItem->getEventUser());
    }
}
