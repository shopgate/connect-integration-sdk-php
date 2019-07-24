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

use Shopgate\ConnectSdk\Dto\Catalog\Inventory;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest;

class InventoryTest extends CatalogTest
{
    /**
     * @throws Exception
     */
    public function testCreateInventoryDirect()
    {
        // Arrange
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $this->createLocation(self::LOCATION_CODE);
        $inventories = $this->provideSampleInventories(1);

        // Act
        $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventory']);
        $currentInventory = $product->getInventory()[0];

        $this->assertEquals(self::LOCATION_CODE, $currentInventory->getLocationCode());
        $this->assertEquals('SKU_1', $currentInventory->getSku());
        $this->assertEquals(11, $currentInventory->getOnHand());
        $this->assertEquals(0, $currentInventory->getOnReserve());
        $this->assertEquals(1, $currentInventory->getSafetyStock());
        $this->assertEquals(11, $currentInventory->getAvailable());
        $this->assertEquals(10, $currentInventory->getVisible());
        $this->assertEquals('1', $currentInventory->getBin());
        $this->assertEquals('DE-1', $currentInventory->getBinLocation());

        // CleanUp
        $this->deleteLocation(self::LOCATION_CODE);
    }

    /**
     * @throws Exception
     */
    public function testDeleteInventoryDirect()
    {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);
        $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);
        $delete = new Inventory\Delete();
        $delete->setProductCode(self::PRODUCT_CODE);
        $delete->setLocationCode(self::LOCATION_CODE);
        $delete->setSku('SKU_1');

        // Act
        $this->sdk->getCatalogService()->deleteInventories([$delete], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventory']);
        $this->assertCount(0, $product->getInventory());

        // CleanUp
        $this->deleteLocation(self::LOCATION_CODE);
    }

    /**
     * @throws Exception
     */
    public function testUpdateInventoryIncrementDirect()
    {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);
        $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);

        // Act
        $update = new Inventory\Update();
        $update->setProductCode(self::PRODUCT_CODE);
        $update->setLocationCode(self::LOCATION_CODE);
        $update->setSku('SKU_1');
        $update->setOperationType(Inventory\Update::OPERATION_TYPE_INCREMENT);
        $update->setOnHand(10);

        $this->sdk->getCatalogService()->updateInventories([$update], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventory']);

        $currentInventory = $product->getInventory()[0];
        $this->assertEquals(self::LOCATION_CODE, $currentInventory->getLocationCode());
        $this->assertEquals('SKU_1', $currentInventory->getSku());
        $this->assertEquals(21, $currentInventory->getOnHand());
        $this->assertEquals(0, $currentInventory->getOnReserve());
        $this->assertEquals(1, $currentInventory->getSafetyStock());
        $this->assertEquals(21, $currentInventory->getAvailable());
        $this->assertEquals(20, $currentInventory->getVisible());
        $this->assertEquals('1', $currentInventory->getBin());
        $this->assertEquals('DE-1', $currentInventory->getBinLocation());

        // CleanUp
        $this->deleteLocation(self::LOCATION_CODE);
    }

    /**
     * @throws Exception
     */
    public function testInvalidLocationCode()
    {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);
        $inventories[0]->setLocationCode('INVALID');

        // Act
        try {
            $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);
        } catch (NotFoundException $exception) {
            $this->assertEquals(
                '{"code":"NotFound","message":"Locations not found: INVALID"}',
                $exception->getMessage()
            );

            return;
        } finally {
            // CleanUp
            $this->deleteEntitiesAfterTestRun(
                self::CATALOG_SERVICE,
                self::METHOD_DELETE_PRODUCT,
                [self::PRODUCT_CODE]
            );
            $this->deleteLocation(self::LOCATION_CODE);
        }

        $this->fail('Expected NotFoundException but wasn\'t thrown');
    }

    /**
     * @throws Exception
     */
    public function testInvalidProductCode()
    {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);
        $inventories[0]->setProductCode('INVALID');

        // Act
        try {
            $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);
        } catch (NotFoundException $exception) {
            $this->assertEquals(
                '{"code":"NotFound","message":"Products not found: INVALID"}',
                $exception->getMessage()
            );

            return;
        } finally {
            // CleanUp
            $this->deleteEntitiesAfterTestRun(
                self::CATALOG_SERVICE,
                self::METHOD_DELETE_PRODUCT,
                [self::PRODUCT_CODE]
            );
            $this->deleteLocation(self::LOCATION_CODE);
        }

        $this->fail('Expected NotFoundException but wasn\'t thrown');
    }

    /**
     * @throws Exception
     */
    public function testUpdateInventoryDecrementDirect()
    {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);
        $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);

        $update = new Inventory\Update();
        $update->setProductCode(self::PRODUCT_CODE);
        $update->setLocationCode(self::LOCATION_CODE);
        $update->setSku('SKU_1');
        $update->setOperationType(Inventory\Update::OPERATION_TYPE_DECREMENT);
        $update->setOnHand(5);

        // Act
        $this->sdk->getCatalogService()->updateInventories([$update], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventory']);

        $currentInventory = $product->getInventory()[0];
        $this->assertEquals(self::LOCATION_CODE, $currentInventory->getLocationCode());
        $this->assertEquals('SKU_1', $currentInventory->getSku());
        $this->assertEquals(6, $currentInventory->getOnHand());
        $this->assertEquals(0, $currentInventory->getOnReserve());
        $this->assertEquals(1, $currentInventory->getSafetyStock());
        $this->assertEquals(6, $currentInventory->getAvailable());
        $this->assertEquals(5, $currentInventory->getVisible());
        $this->assertEquals('1', $currentInventory->getBin());
        $this->assertEquals('DE-1', $currentInventory->getBinLocation());

        // CleanUp
        $this->deleteLocation(self::LOCATION_CODE);
    }

    /**
     * @param array            $inventoryData
     * @param RequestException $expectedException
     * @param string           $missingItem
     *
     * @throws Exception
     *
     * @dataProvider provideCreateInventoryWithMissingRequiredFields
     */
    public function testCreateInventoryDirectWithMissingRequiredFields(
        array $inventoryData,
        $expectedException,
        $missingItem
    ) {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventory = new Inventory\Create($inventoryData);

        // Act
        try {
            $this->sdk->getCatalogService()->addInventories(
                [$inventory],
                [
                    'requestType' => 'direct',
                ]
            );
        } catch (RequestException $exception) {
            // Assert
            $errors = \GuzzleHttp\json_decode($exception->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals('Missing required property: ' . $missingItem, $message);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        } finally {
            // CleanUp
            $this->deleteEntitiesAfterTestRun(
                self::CATALOG_SERVICE,
                self::METHOD_DELETE_PRODUCT,
                [self::PRODUCT_CODE]
            );
            $this->deleteLocation(self::LOCATION_CODE);
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @param array $inventoryData
     * @param int   $expectedOnHand
     * @param int   $expectedSafetyStock
     * @param int   $expectedAvailable
     * @param int   $expectedVisible
     *
     * @throws Exception
     *
     * @dataProvider provideTestInventoryCalculationWithoutSafety
     */
    public function testInventoryCalculationWithoutSafety(
        $inventoryData,
        $expectedOnHand,
        $expectedSafetyStock,
        $expectedAvailable,
        $expectedVisible
    ) {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);

        $inventories[0]->setOnHand(10);
        $inventories[0]->setSafetyStock(0);

        $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);
        $inventory = new Inventory\Create($inventoryData);

        // Act
        $this->sdk->getCatalogService()->updateInventories([$inventory], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventory']);

        $inventory = $product->getInventory();
        $currentInventory = new Inventory($inventory[0]);

        $this->assertEquals($expectedOnHand, $currentInventory->onHand);
        $this->assertEquals($expectedAvailable, $currentInventory->available);
        $this->assertEquals($expectedSafetyStock, $currentInventory->safetyStock);
        $this->assertEquals($expectedVisible, $currentInventory->visible);

        // CleanUp
        $this->deleteLocation(self::LOCATION_CODE);
    }

    /**
     * @param array $inventoryData
     * @param int   $expectedOnHand
     * @param int   $expectedSafetyStock
     * @param int   $expectedAvailable
     * @param int   $expectedVisible
     *
     * @throws Exception
     *
     * @dataProvider provideTestInventoryCalculationWithSafety
     */
    public function testInventoryCalculationWithSafety(
        $inventoryData,
        $expectedOnHand,
        $expectedSafetyStock,
        $expectedAvailable,
        $expectedVisible
    ) {
        // Arrange
        $this->createLocation(self::LOCATION_CODE);
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $inventories = $this->provideSampleInventories(1);

        $inventories[0]->setOnHand(10);
        $inventories[0]->setSafetyStock(2);

        $this->sdk->getCatalogService()->addInventories($inventories, ['requestType' => 'direct']);
        $inventory = new Inventory\Create($inventoryData);

        // Act
        $this->sdk->getCatalogService()->updateInventories([$inventory], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventory']);

        $currentInventory = $product->getInventory()[0];
        $this->assertEquals($expectedOnHand, $currentInventory->getOnHand());
        $this->assertEquals($expectedAvailable, $currentInventory->getAvailable());
        $this->assertEquals($expectedSafetyStock, $currentInventory->getSafetyStock());
        $this->assertEquals($expectedVisible, $currentInventory->getVisible());

        // CleanUp
        $this->deleteLocation(self::LOCATION_CODE);
    }

    /**
     * @return array
     */
    public function provideTestInventoryCalculationWithoutSafety()
    {
        return [
            'increment 10' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_INCREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 10,
                ],
                'expectedOnHand' => 20,
                'expectedSafetyStock' => 0,
                'expectedAvailable' => 20,
                'expectedVisible' => 20,
            ],
            'increment 20' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_INCREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 20,
                ],
                'expectedOnHand' => 30,
                'expectedSafetyStock' => 0,
                'expectedAvailable' => 30,
                'expectedVisible' => 30,
            ],
            'decrement 2' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_DECREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 2,
                ],
                'expectedOnHand' => 8,
                'expectedSafetyStock' => 0,
                'expectedAvailable' => 8,
                'expectedVisible' => 8,
            ],
            'decrement 20' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_DECREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 20,
                ],
                'expectedOnHand' => -10,
                'expectedSafetyStock' => 0,
                'expectedAvailable' => 0,
                'expectedVisible' => 0,
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideTestInventoryCalculationWithSafety()
    {
        return [
            'increment 10' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_INCREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 10,
                ],
                'expectedOnHand' => 20,
                'expectedSafetyStock' => 2,
                'expectedAvailable' => 20,
                'expectedVisible' => 18,
            ],
            'increment 20' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_INCREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 20,
                ],
                'expectedOnHand' => 30,
                'expectedSafetyStock' => 2,
                'expectedAvailable' => 30,
                'expectedVisible' => 28,
            ],
            'decrement 2' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_DECREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 2,
                ],
                'expectedOnHand' => 8,
                'expectedSafetyStock' => 2,
                'expectedAvailable' => 8,
                'expectedVisible' => 6,
            ],
            'decrement 20' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                    'operationType' => Inventory\Create::OPERATION_TYPE_DECREMENT,
                    'sku' => 'SKU_1',
                    'onHand' => 20,
                ],
                'expectedOnHand' => -10,
                'expectedSafetyStock' => 2,
                'expectedAvailable' => 0,
                'expectedVisible' => 0,
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideCreateInventoryWithMissingRequiredFields()
    {
        return [
            'missing productCode' => [
                'inventoryData' => [
                    'locationCode' => self::LOCATION_CODE,
                    'sku' => 'SKU_1',
                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'productCode',
            ],
            'missing locationCode' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'sku' => 'SKU_1',
                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'locationCode',
            ],
            'missing sku' => [
                'inventoryData' => [
                    'productCode' => self::PRODUCT_CODE,
                    'locationCode' => self::LOCATION_CODE,
                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'sku',
            ],
        ];
    }
}
