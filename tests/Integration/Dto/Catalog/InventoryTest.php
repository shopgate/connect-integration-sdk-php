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
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE], $this->getDeleteInventories($inventories));

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventories']);
        $currentInventories = $product->getInventories()[0];

        $this->assertEquals(self::LOCATION_CODE, $currentInventories->getLocationCode());
        $this->assertEquals('SKU_1', $currentInventories->getSku());
        $this->assertEquals(11, $currentInventories->getOnHand());
        $this->assertEquals(0, $currentInventories->getOnReserve());
        $this->assertEquals(1, $currentInventories->getSafetyStock());
        $this->assertEquals(11, $currentInventories->getAvailable());
        $this->assertEquals(10, $currentInventories->getVisible());
        $this->assertEquals('1', $currentInventories->getBin());
        $this->assertEquals('DE-1', $currentInventories->getBinLocation());
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
        $delete->setBin('1');
        $delete->setBinLocation('DE-1');

        // Act
        $this->sdk->getCatalogService()->deleteInventories([$delete], ['requestType' => 'direct']);

        // CleanUp
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventories']);
        $this->assertCount(0, $product->getInventories());
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
        $update->setBinLocation('DE-1');
        $update->setBin('1');
        $update->setOnHand(10);

        $this->sdk->getCatalogService()->updateInventories([$update], ['requestType' => 'direct']);

        // CleanUp
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE], $this->getDeleteInventories($inventories));

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventories']);

        $currentInventories = $product->getInventories()[0];
        $this->assertEquals(self::LOCATION_CODE, $currentInventories->getLocationCode());
        $this->assertEquals('SKU_1', $currentInventories->getSku());
        $this->assertEquals(21, $currentInventories->getOnHand());
        $this->assertEquals(0, $currentInventories->getOnReserve());
        $this->assertEquals(1, $currentInventories->getSafetyStock());
        $this->assertEquals(21, $currentInventories->getAvailable());
        $this->assertEquals(20, $currentInventories->getVisible());
        $this->assertEquals('1', $currentInventories->getBin());
        $this->assertEquals('DE-1', $currentInventories->getBinLocation());
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
            return;
        } finally {
            // CleanUp
            $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE]);
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
            return;
        } finally {
            // CleanUp
            $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE]);
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
        $update->setBin('1');
        $update->setBinLocation('DE-1');

        // Act
        $this->sdk->getCatalogService()->updateInventories([$update], ['requestType' => 'direct']);

        // CleanUp
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE], $this->getDeleteInventories($inventories));

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventories']);

        $currentInventories = $product->getInventories()[0];
        $this->assertEquals(self::LOCATION_CODE, $currentInventories->getLocationCode());
        $this->assertEquals('SKU_1', $currentInventories->getSku());
        $this->assertEquals(6, $currentInventories->getOnHand());
        $this->assertEquals(0, $currentInventories->getOnReserve());
        $this->assertEquals(1, $currentInventories->getSafetyStock());
        $this->assertEquals(6, $currentInventories->getAvailable());
        $this->assertEquals(5, $currentInventories->getVisible());
        $this->assertEquals('1', $currentInventories->getBin());
        $this->assertEquals('DE-1', $currentInventories->getBinLocation());
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
            $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE]);
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
        $inventory = new Inventory\Update($inventoryData);

        // Act
        $this->sdk->getCatalogService()->updateInventories([$inventory], ['requestType' => 'direct']);

        // CleanUp
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE], $this->getDeleteInventories($inventories));

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventories']);

        $updatedInventories = $product->getInventories();
        $currentInventory = new Inventory($updatedInventories[0]);

        $this->assertEquals($expectedOnHand, $currentInventory->onHand);
        $this->assertEquals($expectedAvailable, $currentInventory->available);
        $this->assertEquals($expectedSafetyStock, $currentInventory->safetyStock);
        $this->assertEquals($expectedVisible, $currentInventory->visible);
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
        $this->cleanUp([self::PRODUCT_CODE], [self::LOCATION_CODE], $this->getDeleteInventories($inventories));

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(self::PRODUCT_CODE, ['fields' => 'inventories']);

        $currentInventories = $product->getInventories()[0];
        $this->assertEquals($expectedOnHand, $currentInventories->getOnHand());
        $this->assertEquals($expectedAvailable, $currentInventories->getAvailable());
        $this->assertEquals($expectedSafetyStock, $currentInventories->getSafetyStock());
        $this->assertEquals($expectedVisible, $currentInventories->getVisible());
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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
                    'bin' => '1',
                    'binLocation' => 'DE-1',
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

    /**
     * @param string[] $productCodes
     * @param string[] $locationCodes
     * @param stirng[] $inventories
     */
    private function cleanUp($productCodes = [], $locationCodes = [], $inventories = [])
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
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_INVENTORIES,
            $inventories
        );
    }

    /**
     * @param Inventory\Create[] $createInventories
     *
     * @return Inventory\Delete[]
     */
    private function getDeleteInventories(array $createInventories)
    {
        $deleteInventories = [];
        foreach ($createInventories as $createInventory) {
            $deleteInventory = new Inventory\Delete();
            $deleteInventory->setProductCode($createInventory->productCode);
            $deleteInventory->setLocationCode($createInventory->locationCode);
            $deleteInventory->setSku($createInventory->sku);
            $deleteInventory->setBin($createInventory->bin);
            $deleteInventory->setBinLocation($createInventory->binLocation);

            $deleteInventories[] = [$deleteInventory];
        }

        return $deleteInventories;
    }
}
