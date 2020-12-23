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

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\OrderUtility;

class FulfillmentTest extends OrderUtility
{
    /**
     * @throws Exception
     */
    public function testGetFulfillmentOrder()
    {
        $returnedFulfillmentOrder = $this->sdk->getOrderService()->getFulfillmentOrder('10138-0001');

        $fulfillmentItem = $returnedFulfillmentOrder->getFulfillments()[0];
        $fulfillmentItemPackage = $fulfillmentItem->getFulfillmentPackages()[0];
        $fulfillmentItemPackageLineItem = $fulfillmentItemPackage->getPackageItems()[0];

        $this->assertEquals('10138-0001', $returnedFulfillmentOrder->getOrderNumber());
        $this->assertEquals(1, $fulfillmentItem->getId());
        $this->assertEquals(1, $fulfillmentItemPackage->getId());
        $this->assertEquals(1, $fulfillmentItemPackageLineItem->getId());
        $this->assertEquals(1, $fulfillmentItemPackageLineItem->getLineItemId());
        $this->assertEquals(1.0, $fulfillmentItemPackageLineItem->getQuantity());
    }

    /**
     * @throws Exception
     */
    public function testGetFulfillmentOrders()
    {
        $returnedFulfillmentOrder = $this->sdk->getOrderService()->getFulfillmentOrders();
        $this->assertEquals('10138-0001', $returnedFulfillmentOrder->getFulfillmentOrders()[0]->getOrderNumber());
    }

    /**
     * @throws Exception
     */
    public function testGetFulfillmentOrderStatusCount()
    {
        // Act
        $response = $this->sdk->getOrderService()->getFulfillmentOrderStatusCount();
        $orderStatusCount = $response->getOrderStatusCount();

        // Assert
        $this->assertCount(1, $orderStatusCount);
        $this->assertEquals('fulfilled', $orderStatusCount[0]->getStatus());
        $this->assertEquals(1, $orderStatusCount[0]->getCount());
    }

    /**
     * @throws Exception
     */
    public function testGetFulfillmentOrderBreakdown()
    {
        // Act
        $response = $this->sdk->getOrderService()->getFulfillmentOrderBreakdown('today');

        // Assert
        $this->assertEquals(0, $response->getNumberOfOrders());
        $this->assertEquals(0, $response->getAverageNumberOfItems());
        $this->assertEquals(0, $response->getAverageOrderValue());
    }
}
