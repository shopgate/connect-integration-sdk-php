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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Catalog;

use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price;
use Shopgate\ConnectSdk\Dto\Catalog\Reservation\Get;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Order\Order;
use Shopgate\ConnectSdk\Dto\Order\Order\Dto\Address;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest;

class ReservationTest extends CatalogTest
{
    /**
     * @throws Exception
     *
     * @covers \Shopgate\ConnectSdk\Service\Catalog::addReservations()
     * @covers \Shopgate\ConnectSdk\Service\Catalog::getReservations()
     * @covers \Shopgate\ConnectSdk\Service\Catalog::deleteReservations()
     */
    public function testCreateGetDeleteReservation()
    {
        // Arrange
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $this->createLocation(self::LOCATION_CODE);
        $this->sdk->getCatalogService()->addInventories($this->provideSampleInventories(1));

        $order['customerId'] = $this->createCustomer();
        $orderNumber = $this->createSampleOrder([self::PRODUCT_CODE], self::LOCATION_CODE, $order);

        $reservations = $this->provideSampleReservations(1, self::PRODUCT_CODE, $orderNumber);

        // Act
        $this->sdk->getCatalogService()->addReservations($reservations);

        // Assert
        $reservations = $this->sdk->getCatalogService()->getReservations()->getReservations();

        /** @var Get $currentReservation */
        $currentReservation = array_pop($reservations);

        $this->assertEquals(self::LOCATION_CODE, $currentReservation->getLocationCode());
        $this->assertEquals(self::PRODUCT_CODE, $currentReservation->getProductCode());
        $this->assertEquals('SKU_1', $currentReservation->getSku());
        $this->assertEquals(1, $currentReservation->getQuantity());
        $this->assertEquals('11111-2222-44444-1', $currentReservation->getSalesOrderLineItemCode());
        $this->assertEquals($orderNumber, $currentReservation->getSalesOrderNumber());

        // CleanUp
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE], [$order['customerId']]);
        $this->sdk->getCatalogService()->deleteReservations([$currentReservation->getCode()]);

        $this->assertEmpty($this->sdk->getCatalogService()->getReservations()->getReservations());
    }

    /**
     * @return mixed
     * @throws \Shopgate\ConnectSdk\Exception\AuthenticationInvalidException
     * @throws \Shopgate\ConnectSdk\Exception\NotFoundException
     * @throws \Shopgate\ConnectSdk\Exception\RequestException
     * @throws \Shopgate\ConnectSdk\Exception\UnknownException
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
     * @param string $locationCode
     * @param array $fields
     *
     * @return mixed
     * @throws \Shopgate\ConnectSdk\Exception\AuthenticationInvalidException
     * @throws \Shopgate\ConnectSdk\Exception\NotFoundException
     * @throws \Shopgate\ConnectSdk\Exception\RequestException
     * @throws \Shopgate\ConnectSdk\Exception\UnknownException
     */
    private function createSampleOrder($productIds, $locationCode, $fields = [])
    {
        $defaultFields = [
            'localeCode' => 'en-us',
            'currencyCode' => Price::CURRENCY_CODE_EUR,
            'addressSequences' => [
                new Address([
                    'type' => Order::ADDRESS_TYPE_BILLING,
                    'firstName' => 'Jane',
                    'lastName' => 'Doe'
                ])
            ],
            'primaryBillToAddressSequenceIndex' => 0,
            'subTotal' => '90.00',
            'total' => '90.00',
            'submitDate' => date('c')
        ];
        $order = new Order\Create(array_merge($defaultFields, $fields));
        $order->setLineItems($this->createSampleLineItems($productIds, $locationCode));

        $response = $this->sdk->getOrderService()->addOrders([$order]);

        return $response['orderNumbers'][0];
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
            $sampleLineItems[] = new Order\Dto\LineItem([
                'code' => 'lineItem-' . $productId,
                'quantity' => 2,
                'fulfillmentMethod' => Order\Dto\LineItem::FULFILLMENT_METHOD_ROPIS,
                'shipToAddressSequenceIndex' => 0,
                'fulfillmentLocationCode' => $locationCode,
                'product' => new Order\Dto\LineItem\Product([
                    'code' => $productId,
                    'name' => 'product name ' . $productId,
                    'image' => 'https://myawesomeshop.com/images/img1.jpg',
                    'price' => 90,
                    'currencyCode' => Price::CURRENCY_CODE_EUR
                ]),
                'currencyCode' => Price::CURRENCY_CODE_EUR,
                'price' => 90
            ]);
        }

        return $sampleLineItems;
    }

    /**
     * @param string[] $productCodes
     * @param string[] $locationCodes
     * @param string[] $customerIds
     */
    private function cleanUp($productCodes = [], $locationCodes = [], $customerIds = [])
    {
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            $productCodes
        );
        $this->deleteEntitiesAfterTestRun(
            self::LOCATION_SERVICE,
            self::METHOD_DELETE_LOCATION,
            $locationCodes
        );

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $customerIds
        );
    }
}
