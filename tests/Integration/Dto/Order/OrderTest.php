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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Order;

use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Location\Location;
use Shopgate\ConnectSdk\Dto\Order\Order;
use Shopgate\ConnectSdk\Exception;
use Shopgate\ConnectSdk\Tests\Integration\OrderTest as OrderBaseTest;

class OrderTest extends OrderBaseTest
{
    /**
     * @param string[] $productIds
     * @param string $locationCode
     * @param array $orders
     *
     * @throws Exception\Exception
     *
     * @dataProvider createOrderDataProvider
     */
    public function testCreateOrder($productIds, $locationCode, $orders)
    {
        // Arrange
        $customerId = $this->createCustomer();
        $this->addSampleLocation($locationCode);
        $this->addSampleProducts($productIds);
        $createOrders = [];
        foreach ($orders as $order) {
            $order['customerId'] = $customerId;
            $createOrders[] = $this->createSampleOrder($productIds, $locationCode, $order);
        }

        // CleanUp
        $this->cleanUp([$customerId], $productIds, [$locationCode]);

        // Assert
        $response = $this->sdk->getOrderService()->addOrders($createOrders);
        $this->assertCount(count($orders), $response['orderNumbers']);
    }

    /**
     * @return array
     */
    public function createOrderDataProvider()
    {
        return [
            'add one order' => [
                'productIds' => ['123'],
                'locationCode' => self::LOCATION_CODE,
                'orders' => [
                    ['externalCode' => md5(date('c') . mt_rand())]
                ]
            ],
            'add two orders' => [
                'productIds' => ['123', '321'],
                'locationCode' => self::LOCATION_CODE,
                'orders' => [
                    ['externalCode' => md5(date('c') . mt_rand())],
                    [
                        'externalCode' => md5(date('c') . mt_rand()),
                        'addressSequences' => [
                            new Order\Dto\Address([
                                'type' => Order\Dto\Address::TYPE_BILLING,
                                'firstName' => 'Johnny',
                                'lastName' => 'Bravo'
                            ])
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetOrder()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $this->addSampleLocation(self::LOCATION_CODE);
        $productIds = ['987'];
        $this->addSampleProducts($productIds);
        $externalCode = 'external-get-test-code';
        $order = $this->createSampleOrder(
            $productIds,
            self::LOCATION_CODE,
            ['externalCode' => $externalCode, 'customerId' => $customerId]
        );
        $response = $this->sdk->getOrderService()->addOrders([$order]);
        $orderId = array_pop($response['orderNumbers']);
        // CleanUp
        $this->cleanUp([$customerId], $productIds, [self::LOCATION_CODE]);

        // Assert
        $returnedOrder = $this->sdk->getOrderService()->getOrder($orderId);
        $this->assertEquals($externalCode, $returnedOrder->getExternalCode());
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetFulfillmentOrder()
    {
        $returnedFulfillmentOrder = $this->sdk->getOrderService()->getFulfillmentOrder('10138-0001');
        $this->assertEquals('10138-0001', $returnedFulfillmentOrder->getOrderNumber());
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetFulfillmentOrders()
    {
        $returnedFulfillmentOrder = $this->sdk->getOrderService()->getFulfillmentOrders();
        $this->assertEquals('10138-0001', $returnedFulfillmentOrder->getFulfillmentOrders()[0]->getOrderNumber());
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetOrdersByCustomerId()
    {
        // Arrange
        $customerOneId = $this->createCustomer();
        $customerTwoId = $this->createCustomer();
        $this->addSampleLocation(self::LOCATION_CODE);
        $productIds = ['951', '753'];
        $this->addSampleProducts($productIds);
        $orders = [
            $this->createSampleOrder($productIds, self::LOCATION_CODE, ['customerId' => $customerOneId]),
            $this->createSampleOrder($productIds, self::LOCATION_CODE, ['customerId' => $customerOneId]),
            $this->createSampleOrder($productIds, self::LOCATION_CODE, ['customerId' => $customerTwoId]),
        ];
        $this->sdk->getOrderService()->addOrders($orders);

        // CleanUp
        $this->cleanUp(
            [$customerOneId, $customerTwoId],
            $productIds,
            [self::LOCATION_CODE]
        );

        // Assert
        $responseForCustomerOne = $this->sdk->getOrderService()->getOrders(
            ['filters' => ['customerId' => $customerOneId]]
        );
        $responseForCustomerTwo = $this->sdk->getOrderService()->getOrders(
            ['filters' => ['customerId' => $customerTwoId]]
        );

        $this->assertCount(2, $responseForCustomerOne->getOrders());
        $this->assertCount(1, $responseForCustomerTwo->getOrders());
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetOrdersByExternalCode()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $this->addSampleLocation(self::LOCATION_CODE);
        $productIds = ['951', '753'];
        $externalCodeOne = md5(date('c') . '1');
        $externalCodeTwo = md5(date('c') . '2');
        $this->addSampleProducts($productIds);
        $orders = [
            $this->createSampleOrder(
                $productIds,
                self::LOCATION_CODE,
                [
                    'customerId' => $customerId,
                    'externalCode' => $externalCodeOne
                ]
            ),
            $this->createSampleOrder(
                $productIds,
                self::LOCATION_CODE,
                [
                    'customerId' => $customerId,
                    'externalCode' => $externalCodeTwo
                ]
            )
        ];
        $this->sdk->getOrderService()->addOrders($orders);

        // CleanUp
        $this->cleanUp(
            [$customerId],
            $productIds,
            [self::LOCATION_CODE]
        );

        // Assert
        $responseForExternalCodeOne = $this->sdk->getOrderService()->getOrders(
            ['filters' => ['externalCode' => $externalCodeOne]]
        );
        $responseForExternalCodeTwo = $this->sdk->getOrderService()->getOrders(
            ['filters' => ['externalCode' => $externalCodeTwo]]
        );

        $this->assertCount(1, $responseForExternalCodeOne->getOrders());
        $this->assertCount(1, $responseForExternalCodeTwo->getOrders());
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetOrdersLimitOffset()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $this->addSampleLocation(self::LOCATION_CODE);
        $productIds = ['123', '321'];
        $this->addSampleProducts($productIds);
        $orders = [];
        for ($num = 0; $num < 10; $num++) {
            $orders[] = $this->createSampleOrder(
                $productIds,
                self::LOCATION_CODE,
                ['customerId' => $customerId, 'externalCode' => md5(date('c') . 'limit' . $num)]
            );
        }
        $this->sdk->getOrderService()->addOrders($orders);

        // CleanUp
        $this->cleanUp(
            [$customerId],
            $productIds,
            [self::LOCATION_CODE]
        );

        // Assert
        $responseOne = $this->sdk->getOrderService()->getOrders(['limit' => 3]);
        $responseTwo = $this->sdk->getOrderService()->getOrders(['offset' => 1]);
        $responseThree = $this->sdk->getOrderService()->getOrders(['offset' => 2, 'limit' => 6]);
        $this->assertCount(3, $responseOne->getOrders());
        $this->assertGreaterThanOrEqual(9, count($responseTwo->getOrders()));
        $this->assertCount(6, $responseThree->getOrders());
        $this->assertEquals($responseOne->getOrders()[1], $responseTwo->getOrders()[0]);
        $this->assertEquals($responseOne->getOrders()[2], $responseThree->getOrders()[0]);
    }

    /**
     * @param string[] $productIds
     * @param string $locationCode
     * @param array $fields
     *
     * @return Order\Create
     */
    private function createSampleOrder($productIds, $locationCode, $fields = [])
    {
        $defaultFields = [
            'localeCode' => 'en-us',
            'currencyCode' => 'USD',
            'addressSequences' => [
                new Order\Dto\Address([
                    'type' => Order\Dto\Address::TYPE_BILLING,
                    'firstName' => 'Jane',
                    'lastName' => 'Doe'
                ])
            ],
            'primaryBillToAddressSequenceIndex' => 0,
            'subTotal' => count($productIds) * 90,
            'total' => count($productIds) * 90,
            'submitDate' => date('c')
        ];
        $order = new Order\Create(array_merge($defaultFields, $fields));
        $order->setLineItems($this->createSampleLineItems($productIds, $locationCode));

        return $order;
    }

    /**
     * @return string customer id
     *
     * @throws Exception\Exception
     */
    private function createCustomer()
    {
        $customer = new Customer\Create();
        $customer->setFirstName('John');
        $customer->setLastName('Doe');
        $customer->setEmailAddress('integration-test@shopgate.com');

        $response = $this->sdk->getCustomerService()->addCustomers([$customer]);

        return array_pop($response['ids']);
    }

    /**
     * @param string[] $productIds
     *
     * @throws Exception\Exception
     */
    private function addSampleProducts($productIds)
    {
        if (empty($productIds)) {
            return;
        }
        $this->sdk->getCatalogService()->addProducts(
            $this->createSampleProducts($productIds),
            ['requestType' => 'direct']
        );
    }

    /**
     * @param string[] $productIds
     *
     * @return Product\Create[]
     */
    private function createSampleProducts($productIds)
    {
        return array_map([$this, 'createSampleProduct'], $productIds);
    }

    /**
     * @param string $productId
     *
     * @return Product\Create
     */
    private function createSampleProduct($productId)
    {
        $sampleProduct = new Product\Create();
        $sampleProduct->setCode($productId)
            ->setCatalogCode('my_catalog')
            ->setName(new Product\Dto\Name(['en-us' => 'Test Product']))
            ->setStatus(Product\Create::STATUS_ACTIVE)
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setIsInventoryManaged(true)
            ->setPrice(
                new Product\Dto\Price(
                    [
                        'price' => 90,
                        'currencyCode' => Product\Dto\Price::CURRENCY_CODE_EUR
                    ]
                )
            );

        return $sampleProduct;
    }

    /**
     * @param string[] $productIds
     * @param string $locationCode
     *
     * @return Order\Dto\LineItem[]
     */
    private function createSampleLineItems($productIds, $locationCode)
    {
        $sampleLineItems = [];
        foreach ($productIds as $productId) {
            $sampleLineItems[] = $this->createSampleLineItem($productId, $locationCode);
        }

        return $sampleLineItems;
    }

    /**
     * @param string $productId
     * @param string $locationCode
     *
     * @return Order\Dto\LineItem
     */
    private function createSampleLineItem($productId, $locationCode)
    {
        return new Order\Dto\LineItem([
            'code' => 'lineItem-' . $productId,
            'quantity' => 1,
            'fulfillmentMethod' => Order\Dto\LineItem::FULFILLMENT_METHOD_DIRECT_SHIP,
            'shipToAddressSequenceIndex' => 0,
            'fulfillmentLocationCode' => $locationCode,
            'product' => new Order\Dto\LineItem\Product([
                'code' => $productId,
                'name' => 'product name ' . $productId,
                'image' => 'https://myawesomeshop.com/images/img1.jpg',
                'price' => 90,
                'currencyCode' => 'USD'
            ]),
            'currencyCode' => 'USD',
            'price' => 90
        ]);
    }

    /**
     * @param string $code
     *
     * @throws Exception\Exception
     */
    private function addSampleLocation($code)
    {
        $location = new Location\Create([
            'code' => $code,
            'name' => 'Test Location Name',
            'type' => new Location\Dto\Type(['code' => Location::TYPE_STORE])
        ]);
        $this->sdk->getLocationService()->addLocations([$location]);
    }

    /**
     * @param string[] $customerIds
     * @param string[] $productDeleteIds
     * @param string[] $locationCodes
     */
    private function cleanUp(
        $customerIds = [],
        $productDeleteIds = [],
        $locationCodes = []
    ) {
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $customerIds
        );

        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            $productDeleteIds
        );

        $this->deleteEntitiesAfterTestRun(
            self::LOCATION_SERVICE,
            self::METHOD_DELETE_LOCATION,
            $locationCodes
        );
    }
}
