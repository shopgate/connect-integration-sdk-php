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

use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\LongName;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Update;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest as AbstractCatalogTest;

class ProductTest extends AbstractCatalogTest
{
    /**
     * @throws Exception
     */
    public function testCreateProductMinimumDirect()
    {
        // Arrange
        $product = $this->prepareProductMinimum();

        // Act
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->getCode()]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->getCode());
        $this->assertEquals($product->getCode(), $product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testCreateProductMaximumDirect()
    {
        // Arrange
        $product = $this->prepareProductMaximum();

        // Act
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->getCode()]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->getCode());
        $this->assertEquals($product->getCode(), $product->getCode());
    }

    /**
     * @depends testCreateProductMinimumDirect
     *
     * @throws Exception
     */
    public function testGetProductsWithSpecificCatalogCode()
    {
        // Arrange
        $productMinimum = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$productMinimum], [
            'requestType' => 'direct',
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);

        // Act
        $products = $this->sdk->getCatalogService()->getProducts([
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMinimum->getCode(),
            ],
            self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        );

        // Assert
        $productCodes = [];
        foreach ($products->getProducts() as $product) {
            $productCodes[] = $product->getCode();
        }

        $this->assertCount(1, $products->getProducts());
        $this->assertEquals(
            [
                self::PRODUCT_CODE,
            ],
            $productCodes
        );
    }

    /**
     * @depends testCreateProductMinimumDirect
     * @depends testCreateProductMaximumDirect
     *
     * @throws Exception
     */
    public function testGetProducts()
    {
        // Arrange
        $productMinimum = $this->prepareProductMinimum();
        $productMaximum = $this->prepareProductMaximum();
        $this->sdk->getCatalogService()->addProducts([$productMinimum, $productMaximum], ['requestType' => 'direct']);

        // Act
        $products = $this->sdk->getCatalogService()->getProducts();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMaximum->getCode(),
                $productMinimum->getCode(),
            ]
        );

        // Assert
        $productCodes = [];
        foreach ($products->getProducts() as $product) {
            $productCodes[] = $product->getCode();
        }

        $this->assertCount(2, $products->getProducts());
        $this->assertEquals(
            [
                self::PRODUCT_CODE,
                self::PRODUCT_CODE_SECOND,
            ],
            $productCodes
        );
    }

    /**
     * @throws Exception
     */
    public function testProductAlreadyUpdatedDirect()
    {
        // Arrange
        $productMaximum = $this->prepareProductMaximum(new Update());
        $productMaximum->setExternalUpdateDate('2019-01-01T00:00:00.000Z');
        $this->sdk->getCatalogService()->addProducts(
            [
                $productMaximum,
            ],
            ['requestType' => 'direct']
        );

        $product = new Product\Update(
            [
                'externalUpdateDate' => '2018-12-31T23:59:59.000Z',
            ]
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMaximum->getCode(),
            ]
        );

        // Act
        try {
            $this->sdk->getCatalogService()->updateProduct(
                $productMaximum->getCode(),
                $product,
                [
                    'requestType' => 'direct',
                ]
            );
        } catch (RequestException $exception) {
            // Assert
            $this->assertEquals(409, $exception->getStatusCode());

            return;
        }

        $this->fail('Expected RequestException but wasn\'t thrown');
    }

    /**
     * @throws Exception
     */
    public function testUpdateProductPricingDirect()
    {
        // Arrange
        $productMaximum = $this->prepareProductMaximum(new Update());
        $productMaximum->setModelType(Product::MODEL_TYPE_CONFIGURABLE);
        $this->sdk->getCatalogService()->addProducts(
            [
                $productMaximum,
            ],
            ['requestType' => 'direct']
        );

        $volumePricing = new Product\Dto\Price\VolumePricing();
        $volumePricing->setMinQty(5)
            ->setMaxQty(6)
            ->setPrice(7.7)
            ->setSalePrice(8.8)
            ->setUnit('m')
            ->setPriceType(Product\Dto\Price\VolumePricing::PRICE_TYPE_FIXED);

        $mapPricing = new Product\Dto\Price\MapPricing();
        $mapPricing->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(9.9);

        $price = (new Product\Dto\Price())
            ->setCurrencyCode(Product\Dto\Price::CURRENCY_CODE_USD)
            ->setCost(12.12)
            ->setPrice(13.13)
            ->setSalePrice(14.14)
            ->setVolumePricing([$volumePricing])
            ->setUnit('m')
            ->setMsrp(15.15)
            ->setMapPricing([$mapPricing]);
        $product = new Product\Update();
        $product->setPrice($price);

        // Act
        $this->sdk->getCatalogService()->updateProduct(
            $productMaximum->getCode(),
            $product,
            [
                'requestType' => 'direct',
            ]
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMaximum->getCode(),
            ]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($productMaximum->getCode());
        $updatedProductPrice = $product->getPrice();
        $this->assertEquals($price->getCurrencyCode(), $updatedProductPrice->getCurrencyCode());
        $this->assertEquals($price->getCost(), $updatedProductPrice->getCost());
        $this->assertEquals($price->getPrice(), $updatedProductPrice->getPrice());
        $this->assertEquals($price->getSalePrice(), $updatedProductPrice->getSalePrice());
        $this->assertEquals($price->getUnit(), $updatedProductPrice->getUnit());
        $this->assertEquals($price->getMsrp(), $updatedProductPrice->getMsrp());

        $updatedVolumePrice = $updatedProductPrice->getVolumePricing()[0];
        $this->assertEquals($volumePricing->getPrice(), $updatedVolumePrice->getPrice());
        $this->assertEquals($volumePricing->getSalePrice(), $updatedVolumePrice->getSalePrice());
        $this->assertEquals($volumePricing->getMinQty(), $updatedVolumePrice->getMinQty());
        $this->assertEquals($volumePricing->getMaxQty(), $updatedVolumePrice->getMaxQty());
        $this->assertEquals($volumePricing->getUnit(), $updatedVolumePrice->getUnit());

        $updatedMapPricing = $updatedProductPrice->getMapPricing()[0];
        $this->assertEquals($mapPricing->getPrice(), $updatedMapPricing->getPrice());
        $this->assertEquals($mapPricing->getStartDate(), $updatedMapPricing->getStartDate());
        $this->assertEquals($mapPricing->getEndDate(), $updatedMapPricing->getEndDate());
    }

    /**
     * @param array  $updateProductData
     * @param string $expectedValue
     *
     * @throws Exception
     *
     * @dataProvider provideUpdateProductData
     */
    public function testUpdateProductPropertyDirect(array $updateProductData, $expectedValue)
    {
        // Arrange
        $productMaximum = $this->prepareProductMaximum(new Update());
        $this->sdk->getCatalogService()->addProducts(
            [
                $productMaximum,
            ],
            ['requestType' => 'direct']
        );

        $product = new Product\Update($updateProductData);

        // Act
        $this->sdk->getCatalogService()->updateProduct(
            $productMaximum->getCode(),
            $product,
            [
                'requestType' => 'direct',
            ]
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMaximum->getCode(),
            ]
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($productMaximum->getCode());
        $updatedKey = array_keys($updateProductData)[0];
        $this->assertEquals($expectedValue, $product->get($updatedKey));
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function provideUpdateProductData()
    {
        return [
            'externalUpdateDate' => [
                'updateProductData' => [
                    'externalUpdateDate' => '2020-02-04T00:00:00.000Z',
                ],
                'expectedValue' => '2020-02-04T00:00:00.000Z',
            ],
            'name' => [
                'updateProductData' => [
                    'name' => new Name(['en-us' => 'Updated Name']),
                ],
                'expectedValue' => 'Updated Name',
            ],
            'longName' => [
                'updateProductData' => [
                    'longName' => new LongName(['en-us' => 'Updated Long Name']),
                ],
                'expectedValue' => 'Updated Long Name',
            ],
            'unit' => [
                'updateProductData' => [
                    'unit' => 'm',
                ],
                'expectedValue' => 'm',
            ],
            'url' => [
                'updateProductData' => [
                    'url' => 'http://updated.url.com',
                ],
                'expectedValue' => 'http://updated.url.com',
            ],
            'rating' => [
                'updateProductData' => [
                    'rating' => 2.5,
                ],
                'expectedValue' => 2.5,
            ],
            'isTaxed' => [
                'updateProductData' => [
                    'isTaxed' => false,
                ],
                'expectedValue' => false,
            ],
            'taxClass' => [
                'updateProductData' => [
                    'taxClass' => 'a123456',
                ],
                'expectedValue' => 'a123456',
            ],
            'minQty' => [
                'updateProductData' => [
                    'minQty' => 12,
                ],
                'expectedValue' => 12,
            ],
            'maxQty' => [
                'updateProductData' => [
                    'maxQty' => 122,
                ],
                'expectedValue' => 122,
            ],
            'isInventoryManaged' => [
                'updateProductData' => [
                    'isInventoryManaged' => false,
                ],
                'expectedValue' => false,
            ],
            'startDate' => [
                'updateProductData' => [
                    'startDate' => '2019-12-12T00:00:00.000Z',
                ],
                'expectedValue' => '2019-12-12T00:00:00.000Z',
            ],
            'endDate' => [
                'updateProductData' => [
                    'endDate' => '2016-02-03T00:00:00.000Z',
                ],
                'expectedValue' => '2016-02-03T00:00:00.000Z',
            ],
            'firstAvailableDate' => [
                'updateProductData' => [
                    'firstAvailableDate' => '2019-05-07T00:00:00.000Z',
                ],
                'expectedValue' => '2019-05-07T00:00:00.000Z',
            ],
            'eolDate' => [
                'updateProductData' => [
                    'eolDate' => '2032-11-11T00:00:00.000Z',
                ],
                'expectedValue' => '2032-11-11T00:00:00.000Z',
            ],
            'status scheduled' => [
                'updateProductData' => [
                    'status' => Product::STATUS_SCHEDULED,
                ],
                'expectedValue' => Product::STATUS_SCHEDULED,
            ],
            'status inactive' => [
                'updateProductData' => [
                    'status' => Product::STATUS_INACTIVE,
                ],
                'expectedValue' => Product::STATUS_INACTIVE,
            ],
            'status active' => [
                'updateProductData' => [
                    'status' => Product::STATUS_ACTIVE,
                ],
                'expectedValue' => Product::STATUS_ACTIVE,
            ],
            'inventoryTreatment allow backorders' => [
                'updateProductData' => [
                    'inventoryTreatment' => Product::INVENTORY_TREATMENT_ALLOW_BACK_ORDERS,
                ],
                'expectedValue' => Product::INVENTORY_TREATMENT_ALLOW_BACK_ORDERS,
            ],
            'inventoryTreatment pre order' => [
                'updateProductData' => [
                    'inventoryTreatment' => Product::INVENTORY_TREATMENT_PRE_ORDER,
                ],
                'expectedValue' => Product::INVENTORY_TREATMENT_PRE_ORDER,
            ],
            'inventoryTreatment show out of stock' => [
                'updateProductData' => [
                    'inventoryTreatment' => Product::INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK,
                ],
                'expectedValue' => Product::INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK,
            ],
            'modelType bundle' => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_BUNDLE,
                ],
                'expectedValue' => Product::MODEL_TYPE_BUNDLE,
            ],
            'modelType bundle item' => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_BUNDLE_ITEM,
                ],
                'expectedValue' => Product::MODEL_TYPE_BUNDLE_ITEM,
            ],
            'modelType configurable' => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_CONFIGURABLE,
                ],
                'expectedValue' => Product::MODEL_TYPE_CONFIGURABLE,
            ],
            'modelType standard' => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_STANDARD,
                ],
                'expectedValue' => Product::MODEL_TYPE_STANDARD,
            ],
            'modelType variant' => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_VARIANT,
                ],
                'expectedValue' => Product::MODEL_TYPE_VARIANT,
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function testCreateProductMinimumEvent()
    {
        // Arrange
        $product = $this->prepareProductMinimum();

        // Act
        $this->sdk->getCatalogService()->addProducts([$product]);
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->getCode()]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->getCode());
        $this->assertEquals($product->getCode(), $product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testCreateProductMinimumEventInSpecificCatalogCode()
    {
        // Arrange
        $product = $this->prepareProductMinimum();

        // Act
        $this->sdk->getCatalogService()->addProducts([$product], [
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [$product->getCode()],
            self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        );

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct(
            $product->getCode(),
            [
                'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
            ]
        );
        $this->assertEquals($product->getCode(), $product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testCreateProductMaximumEvent()
    {
        // Arrange
        $product = $this->prepareProductMaximum();

        // Act
        $this->sdk->getCatalogService()->addProducts([$product]);
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->getCode()]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->getCode());
        $this->assertEquals($product->getCode(), $product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testProductNotFoundExceptionDirect()
    {
        // Assert
        $this->expectException(NotFoundException::class);

        // Act
        $this->sdk->getCatalogService()->getProduct('not existent code');
    }

    /**
     * @throws Exception
     */
    public function testDeleteProductDirect()
    {
        // Arrange
        $product = $this->prepareProductMaximum();

        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // Act
        $this->sdk->getCatalogService()->deleteProduct($product->getCode(), ['requestType' => 'direct']);

        // Assert
        $this->expectException(NotFoundException::class);
        $this->sdk->getCatalogService()->getProduct($product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testDeleteProductWithSpaceDirect()
    {
        // Arrange
        $product = $this->prepareProductMaximum();
        $productCode = 'Test Space';
        $product->setCode($productCode);

        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // Act
        $this->sdk->getCatalogService()->deleteProduct($product->getCode(), ['requestType' => 'direct']);

        // Assert
        $this->expectException(NotFoundException::class);
        $this->sdk->getCatalogService()->getProduct($product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testDeleteProductEvent()
    {
        // Arrange
        $product = $this->prepareProductMaximum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // Act
        $this->sdk->getCatalogService()->deleteProduct($product->getCode());
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $this->expectException(NotFoundException::class);
        $this->sdk->getCatalogService()->getProduct($product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testGetProduct()
    {
        // Arrange
        $productMinimum = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$productMinimum], ['requestType' => 'direct']);

        // Act
        $product = $this->sdk->getCatalogService()->getProduct($productMinimum->getCode());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMinimum->getCode(),
            ]
        );

        // Assert
        $this->assertEquals($productMinimum->getCode(), $product->getCode());
    }

    /**
     * @throws Exception
     */
    public function testGetProductWithSingleFieldCode()
    {
        // Arrange
        $productMinimum = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$productMinimum], ['requestType' => 'direct']);

        // Act
        $product = $this->sdk->getCatalogService()->getProduct($productMinimum->getCode(), ['fields' => 'code']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMinimum->getCode(),
            ]
        );

        // Assert
        $this->assertEquals($productMinimum->getCode(), $product->getCode());
        $this->assertEquals(json_encode(['code' => $productMinimum->getCode()], true), $product->toJson());
    }

    /**
     * @param int      $limit
     * @param int      $offset
     * @param int      $expectedProductCount
     * @param string[] $expectedProductCodes
     *
     * @throws Exception
     *
     * @dataProvider provideProductLimitCases
     */
    public function testProductLimit($limit, $offset, $expectedProductCount, $expectedProductCodes)
    {
        // Arrange
        $productMinimum = $this->prepareProductMinimum();
        $productMaximum = $this->prepareProductMaximum();
        $this->sdk->getCatalogService()->addProducts([$productMinimum, $productMaximum], ['requestType' => 'direct']);

        $parameters = [];
        if (isset($limit)) {
            $parameters['limit'] = $limit;
        }
        if (isset($offset)) {
            $parameters['offset'] = $offset;
        }

        // Act
        $products = $this->sdk->getCatalogService()->getProducts(
            $parameters
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMaximum->getCode(),
                $productMinimum->getCode(),
            ]
        );

        // Assert
        $productCodes = [];
        foreach ($products->getProducts() as $product) {
            $productCodes[] = $product->getCode();
        }

        $this->assertCount($expectedProductCount, $products->getProducts());
        $this->assertEquals($expectedProductCodes, $productCodes);
        if (isset($limit)) {
            $this->assertEquals($limit, $products->getMeta()->getLimit());
        }
        if (isset($offset)) {
            $this->assertEquals($offset, $products->getMeta()->getOffset());
        }
    }

    /**
     * @return array
     */
    public function provideProductLimitCases()
    {
        return [
            'get the second' => [
                'limit' => 1,
                'offset' => 1,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'get the first' => [
                'limit' => 1,
                'offset' => 0,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                ],
            ],
            'get two' => [
                'limit' => 2,
                'offset' => 0,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'limit 1' => [
                'limit' => 1,
                'offset' => null,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                ],
            ],
            'limit 2' => [
                'limit' => 2,
                'offset' => null,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'offset 1' => [
                'limit' => null,
                'offset' => 1,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'offset 2' => [
                'limit' => null,
                'offset' => 2,
                'expectedCount' => 0,
                'expectedCodes' => [],
            ],
            'no entities found' => [
                'limit' => 1,
                'offset' => 2,
                'expectedCount' => 0,
                'expectedCodes' => [],
            ],
        ];
    }
}
