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

use Shopgate\ConnectSdk\Dto\Catalog\Reservation\Get;
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
        $reservations = $this->provideSampleReservations(1);

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
        $this->assertEquals('11111-2222-33333-1', $currentReservation->getSalesOrderId());
        $this->assertEquals('11111-2222-22222-1', $currentReservation->getFulfillmentOrderId());

        // CleanUp
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE]);
        $this->sdk->getCatalogService()->deleteReservations([$currentReservation->getCode()]);

        $this->assertEmpty($this->sdk->getCatalogService()->getReservations()->getReservations());
    }

    /**
     * @param string[] $productCodes
     * @param string[] $locationCodes
     */
    private function cleanUp($productCodes = [], $locationCodes = [])
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
    }
}
