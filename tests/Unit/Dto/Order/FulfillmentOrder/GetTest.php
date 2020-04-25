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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\FulfillmentOrder;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Dto as FulfillmentOrderDto;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Get;
use Shopgate\ConnectSdk\Dto\Order\Dto\Fulfillment;
use Shopgate\ConnectSdk\Exception\Exception;

class GetTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testBasicProperties()
    {
        // Arrange
        $entry = [
            'orderNumber' => 'testOrderNumber',
            'externalCode' => 'testExternalCode',
            'posTransactionId' => '528a-1',
            'cancellationReason' => FulfillmentOrder::CANCELLATION_EXPIRED,
            'salesOrderNumber' => '1293747',
            'locationCode' => 'DERetail001',
            'type' => FulfillmentOrder::TYPE_DIRECT_SHIP,
            'customerId' => '55c98b8e-1100-497c-8df6-4b4f0353ab2a',
            'externalCustomerNumber' => 'C1756793',
            'routeType' => FulfillmentOrder::ROUTE_TYPE_STANDARD_DIRECT_SHIP,
            'expedited' => true,
            'status' => FulfillmentOrder::STATUS_NEW,
            'channel' => [
                'name' => 'US Retail',
                'code' => 'USRTL'
            ],
            'taxAmount' => 0,
            'tax2Amount' => 0,
            'total' => 0,
            'shippingTotal' => 0,
            'localeCode' => 'en-us',
            'currencyCode' => 'USD',
            'notes' => 'Some note',
            'specialInstructions' => '',
            'fulfillmentOrderAddress' => [
                'orderIndex' => '123',
                'type' => 'pickup',
                'firstName' => 'John',
                'middleName' => 'D.',
                'lastName' => 'Doe',
                'company' => 'Shopgate Inc',
                'address1' => '12 Somestreet',
                'address2' => 'Unknown Type: string,null',
                'address3' => 'Unknown Type: string,null',
                'address4' => 'Unknown Type: string,null',
                'city' => 'Austin',
                'region' => 'TX',
                'postalCode' => '78732',
                'country' => 'US',
                'phone' => '+1 000-000-0000',
                'fax' => '+1 000-000-0000',
                'mobile' => '+1 000-000-0000',
                'emailAddress' => 'john@doe.com'
            ],
            'fulfillments' => [
                [
                    'id' => '975edd0a-e76c-11e8-8115-063a60f67055',
                    'status' => Fulfillment::STATUS_OPEN,
                    'carrier' => 'DHL',
                    'serviceLevel' => 'sameDay',
                    'tracking' => 'JJD000390007882823450',
                    'fulfillmentPackages' => [
                        [
                            'id' => 789865,
                            'status' => Fulfillment\FulfillmentPackage::STATUS_OPEN,
                            'serviceLevel' => 'string',
                            'fulfilledFromLocationCode' => 'DERetail001',
                            'weight' => 0,
                            'dimensions' => 'string',
                            'tracking' => 'JJD000390007882823450',
                            'pickUpBy' => 'Jane Doe',
                            'labelUrl' => 'https://documentserver.internal/label/label.pdf',
                            'fulfillmentDate' => '2019-09-04T07=>26=>42.535Z',
                            'packageItems' => [
                                [
                                    'id' => 4438756,
                                    'lineItemId' => 3498345,
                                    'quantity' => 5
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'lineItems' => [
                [
                    'id' => '8eb3ba18-e76c-11e8-8115-063a60f67055',
                    'salesOrderLineItemCode' => '386',
                    'sku' => '1337-hoodie-dark-green',
                    'quantity' => 5,
                    'status' => 'string',
                    'currencyCode' => 'USD',
                    'price' => 59.5,
                    'shippingAmount' => 0,
                    'taxAmount' => 0,
                    'tax2Amount' => 0,
                    'taxExempt' => true,
                    'discountAmount' => 0,
                    'promoAmount' => 0,
                    'overrideAmount' => 0,
                    'extendedPrice' => 0,
                    'product' => [
                        'code' => '24-MB02',
                        'name' => 'Fusion Backpack',
                        'image' => 'https://myawesomeshop.com/images/img1.jpg',
                        'price' => 59.5,
                        'currencyCode' => 'USD',
                        'options' => [
                            [
                                'code' => '146',
                                'name' => 'Color',
                                'value' => 'Red',
                            ],
                            [
                                'code' => '677',
                                'name' => 'Value option',
                                'value' => [
                                    'name' => 'Option value name',
                                    'code' => '23987'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'submitDate' => '2019-09-04T07:26:42.535Z',
            'acceptDate' => '2019-09-04T07:26:42.535Z',
            'readyDate' => '2019-09-04T07:26:42.535Z',
            'completeDate' => '2019-09-04T07:26:42.535Z'
        ];

        // Act
        $get = new Get($entry);

        // Assert
        $this->assertEquals($entry['orderNumber'], $get->getOrderNumber());
        $this->assertEquals($entry['externalCode'], $get->getExternalCode());
        $this->assertEquals($entry['posTransactionId'], $get->getPosTransactionId());
        $this->assertEquals($entry['cancellationReason'], $get->getCancellationReason());
        $this->assertEquals($entry['salesOrderNumber'], $get->getSalesOrderNumber());
        $this->assertEquals($entry['locationCode'], $get->getLocationCode());
        $this->assertEquals($entry['type'], $get->getType());
        $this->assertEquals($entry['customerId'], $get->getCustomerId());
        $this->assertEquals($entry['externalCustomerNumber'], $get->getExternalCustomerNumber());
        $this->assertEquals($entry['routeType'], $get->getRouteType());
        $this->assertEquals($entry['expedited'], $get->getExpedited());
        $this->assertEquals($entry['status'], $get->getStatus());
        $this->assertEquals($entry['submitDate'], $get->getSubmitDate());
        $this->assertEquals($entry['acceptDate'], $get->getAcceptDate());
        $this->assertEquals($entry['readyDate'], $get->getReadyDate());
        $this->assertEquals($entry['completeDate'], $get->getCompleteDate());

        $channel = $get->getChannel();
        $this->assertInstanceOf(FulfillmentOrderDto\Channel::class, $channel);
        $this->assertEquals($entry['channel']['name'], $get->getChannel()->getName());
        $this->assertEquals($entry['channel']['code'], $get->getChannel()->getCode());
        $this->assertEquals($entry['taxAmount'], $get->getTaxAmount());
        $this->assertEquals($entry['tax2Amount'], $get->getTax2Amount());
        $this->assertEquals($entry['total'], $get->getTotal());
        $this->assertEquals($entry['shippingTotal'], $get->getShippingTotal());
        $this->assertEquals($entry['localeCode'], $get->getLocaleCode());
        $this->assertEquals($entry['currencyCode'], $get->getCurrencyCode());
        $this->assertEquals($entry['notes'], $get->getNotes());
        $this->assertEquals($entry['specialInstructions'], $get->getSpecialInstructions());

        $fulfillmentOrderAddress = $get->getFulfillmentOrderAddress();
        $this->assertInstanceOf(FulfillmentOrderDto\FulfillmentOrderAddress::class, $fulfillmentOrderAddress);
        $this->assertEquals($entry['fulfillmentOrderAddress']['orderIndex'], $fulfillmentOrderAddress->getOrderIndex());
        $this->assertEquals($entry['fulfillmentOrderAddress']['type'], $fulfillmentOrderAddress->getType());
        $this->assertEquals($entry['fulfillmentOrderAddress']['firstName'], $fulfillmentOrderAddress->getFirstName());
        $this->assertEquals($entry['fulfillmentOrderAddress']['middleName'], $fulfillmentOrderAddress->getMiddleName());
        $this->assertEquals($entry['fulfillmentOrderAddress']['lastName'], $fulfillmentOrderAddress->getLastName());
        $this->assertEquals($entry['fulfillmentOrderAddress']['company'], $fulfillmentOrderAddress->getCompany());
        $this->assertEquals($entry['fulfillmentOrderAddress']['address1'], $fulfillmentOrderAddress->getAddress1());
        $this->assertEquals($entry['fulfillmentOrderAddress']['address2'], $fulfillmentOrderAddress->getAddress2());
        $this->assertEquals($entry['fulfillmentOrderAddress']['address3'], $fulfillmentOrderAddress->getAddress3());
        $this->assertEquals($entry['fulfillmentOrderAddress']['address4'], $fulfillmentOrderAddress->getAddress4());
        $this->assertEquals($entry['fulfillmentOrderAddress']['city'], $fulfillmentOrderAddress->getCity());
        $this->assertEquals($entry['fulfillmentOrderAddress']['region'], $fulfillmentOrderAddress->getRegion());
        $this->assertEquals($entry['fulfillmentOrderAddress']['postalCode'], $fulfillmentOrderAddress->getPostalCode());
        $this->assertEquals($entry['fulfillmentOrderAddress']['country'], $fulfillmentOrderAddress->getCountry());
        $this->assertEquals($entry['fulfillmentOrderAddress']['phone'], $fulfillmentOrderAddress->getPhone());
        $this->assertEquals($entry['fulfillmentOrderAddress']['fax'], $fulfillmentOrderAddress->getFax());
        $this->assertEquals($entry['fulfillmentOrderAddress']['mobile'], $fulfillmentOrderAddress->getMobile());
        $this->assertEquals($entry['fulfillmentOrderAddress']['emailAddress'], $fulfillmentOrderAddress->getEmailAddress());

        // Fulfillments
        $fulfillments = $get->getFulfillments();
        $this->assertTrue(is_array($fulfillments));
        $fulfillment = $fulfillments[0];
        $this->assertInstanceOf(Fulfillment::class, $fulfillment);
        $this->assertEquals($entry['fulfillments'][0]['id'], $fulfillment->getId());
        $this->assertEquals($entry['fulfillments'][0]['status'], $fulfillment->getStatus());
        $this->assertEquals($entry['fulfillments'][0]['carrier'], $fulfillment->getCarrier());
        $this->assertEquals($entry['fulfillments'][0]['serviceLevel'], $fulfillment->getServiceLevel());
        $this->assertEquals($entry['fulfillments'][0]['tracking'], $fulfillment->getTracking());

        // Fulfillment packages
        $fulfillmentPackages = $fulfillment->getFulfillmentPackages();
        $this->assertTrue(is_array($fulfillmentPackages));
        $fulfillmentPackage = $fulfillmentPackages[0];
        $this->assertInstanceOf(Fulfillment\FulfillmentPackage::class, $fulfillmentPackage);
        $this->assertEquals($entry['fulfillments'][0]['fulfillmentPackages'][0]['id'], $fulfillmentPackage->getId());
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['status'],
            $fulfillmentPackage->getStatus()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['serviceLevel'],
            $fulfillmentPackage->getServiceLevel()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['fulfilledFromLocationCode'],
            $fulfillmentPackage->getFulfilledFromLocationCode()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['weight'],
            $fulfillmentPackage->getWeight()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['dimensions'],
            $fulfillmentPackage->getDimensions()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['tracking'],
            $fulfillmentPackage->getTracking()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['pickUpBy'],
            $fulfillmentPackage->getPickUpBy()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['labelUrl'],
            $fulfillmentPackage->getLabelUrl()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['fulfillmentDate'],
            $fulfillmentPackage->getFulfillmentDate()
        );

        // FulfillmentPackageItem
        $fulfillmentPackageItems = $fulfillmentPackage->getPackageItems();
        $this->assertTrue(is_array($fulfillmentPackageItems));
        $fulfillmentPackageItem = $fulfillmentPackageItems[0];
        $this->assertInstanceOf(Fulfillment\FulfillmentPackage\PackageItem::class, $fulfillmentPackageItem);
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['packageItems'][0]['id'],
            $fulfillmentPackageItem->getId()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['packageItems'][0]['lineItemId'],
            $fulfillmentPackageItem->getLineItemId()
        );
        $this->assertEquals(
            $entry['fulfillments'][0]['fulfillmentPackages'][0]['packageItems'][0]['quantity'],
            $fulfillmentPackageItem->getQuantity()
        );

        // LineItems
        $lineItems = $get->getLineItems();
        $this->assertTrue(is_array($lineItems));
        $lineItem = $lineItems[0];
        $this->assertInstanceOf(FulfillmentOrderDto\LineItem::class, $lineItem);
        $this->assertEquals($entry['lineItems'][0]['id'], $lineItem->getId());
        $this->assertEquals($entry['lineItems'][0]['salesOrderLineItemCode'], $lineItem->getSalesOrderLineItemCode());
        $this->assertEquals($entry['lineItems'][0]['sku'], $lineItem->getSku());
        $this->assertEquals($entry['lineItems'][0]['quantity'], $lineItem->getQuantity());
        $this->assertEquals($entry['lineItems'][0]['status'], $lineItem->getStatus());
        $this->assertEquals($entry['lineItems'][0]['currencyCode'], $lineItem->getCurrencyCode());
        $this->assertEquals($entry['lineItems'][0]['price'], $lineItem->getPrice());
        $this->assertEquals($entry['lineItems'][0]['shippingAmount'], $lineItem->getShippingAmount());
        $this->assertEquals($entry['lineItems'][0]['taxAmount'], $lineItem->getTaxAmount());
        $this->assertEquals($entry['lineItems'][0]['tax2Amount'], $lineItem->getTax2Amount());
        $this->assertEquals($entry['lineItems'][0]['taxExempt'], $lineItem->getTaxExempt());
        $this->assertEquals($entry['lineItems'][0]['discountAmount'], $lineItem->getDiscountAmount());
        $this->assertEquals($entry['lineItems'][0]['promoAmount'], $lineItem->getPromoAmount());
        $this->assertEquals($entry['lineItems'][0]['overrideAmount'], $lineItem->getOverrideAmount());
        $this->assertEquals($entry['lineItems'][0]['extendedPrice'], $lineItem->getExtendedPrice());

        // LineItemProducts
        $lineItemProduct = $lineItem->getProduct();
        $this->assertInstanceOf(FulfillmentOrderDto\LineItem\Product::class, $lineItemProduct);
        $this->assertEquals($entry['lineItems'][0]['product']['code'], $lineItemProduct->getCode());
        $this->assertEquals($entry['lineItems'][0]['product']['name'], $lineItemProduct->getName());
        $this->assertEquals($entry['lineItems'][0]['product']['image'], $lineItemProduct->getImage());
        $this->assertEquals($entry['lineItems'][0]['product']['price'], $lineItemProduct->getPrice());
        $this->assertEquals($entry['lineItems'][0]['product']['currencyCode'], $lineItemProduct->getCurrencyCode());

        $productOptions = $lineItemProduct->getOptions();
        $this->assertTrue(is_array($productOptions));
        $productOption = $productOptions[0];
        $this->assertInstanceOf(FulfillmentOrderDto\LineItem\Product\Option::class, $productOption);
        $this->assertEquals($entry['lineItems'][0]['product']['options'][0]['code'], $productOption->getCode());
        $this->assertEquals($entry['lineItems'][0]['product']['options'][0]['name'], $productOption->getName());
        $this->assertEquals($entry['lineItems'][0]['product']['options'][0]['value'], $productOption->getValue());

        $productOption = $productOptions[1];
        $this->assertInstanceOf(FulfillmentOrderDto\LineItem\Product\Option\Value::class, $productOption->getValue());
        $this->assertEquals($entry['lineItems'][0]['product']['options'][1]['value']['name'], $productOption->getValue()->getName());
        $this->assertEquals($entry['lineItems'][0]['product']['options'][1]['value']['code'], $productOption->getValue()->getCode());

    }
}
