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
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Categories;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Extras;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\LongDescription;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\LongName;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\MediaList;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\MapPricing;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\VolumePricing;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\ShortDescription;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Update;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest;

class ProductTest extends CatalogTest
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
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->code);
        $this->assertEquals($product->getCode(), $product->code);
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
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->code);
        $this->assertEquals($product->getCode(), $product->code);
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
                $productMaximum->code,
            ]
        );

        // Act
        try {
            $this->sdk->getCatalogService()->updateProduct(
                $productMaximum->code,
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

        $price   = (new Product\Dto\Price())
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
            $productMaximum->code,
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
                $productMaximum->code,
            ]
        );

        // Assert
        $product             = $this->sdk->getCatalogService()->getProduct($productMaximum->code);
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
            $productMaximum->code,
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
                $productMaximum->code,
            ]
        );

        // Assert
        $product    = $this->sdk->getCatalogService()->getProduct($productMaximum->code);
        $updatedKey = array_keys($updateProductData)[0];
        $this->assertEquals($expectedValue, $product->get($updatedKey));
    }

    /**
     *
     * @return array
     */
    public function provideUpdateProductData()
    {
        return [
            'externalUpdateDate'                   => [
                'updateProductData' => [
                    'externalUpdateDate' => '2020-02-04T00:00:00.000Z',
                ],
                'expectedValue'     => '2020-02-04T00:00:00.000Z',
            ],
            'name'                                 => [
                'updateProductData' => [
                    'name' => new Name(['en-us' => 'Updated Name']),
                ],
                'expectedValue'     => 'Updated Name',
            ],
            'longName'                             => [
                'updateProductData' => [
                    'longName' => new LongName(['en-us' => 'Updated Long Name']),
                ],
                'expectedValue'     => 'Updated Long Name',
            ],
            'unit'                                 => [
                'updateProductData' => [
                    'unit' => 'm',
                ],
                'expectedValue'     => 'm',
            ],
            'url'                                  => [
                'updateProductData' => [
                    'url' => 'http://updated.url.com',
                ],
                'expectedValue'     => 'http://updated.url.com',
            ],
            'rating'                               => [
                'updateProductData' => [
                    'rating' => 2.5,
                ],
                'expectedValue'     => 2.5,
            ],
            'isTaxed'                              => [
                'updateProductData' => [
                    'isTaxed' => false,
                ],
                'expectedValue'     => false,
            ],
            'taxClass'                             => [
                'updateProductData' => [
                    'taxClass' => 'a123456',
                ],
                'expectedValue'     => 'a123456',
            ],
            'minQty'                               => [
                'updateProductData' => [
                    'minQty' => 12,
                ],
                'expectedValue'     => 12,
            ],
            'maxQty'                               => [
                'updateProductData' => [
                    'maxQty' => 122,
                ],
                'expectedValue'     => 122,
            ],
            'isInventoryManaged'                   => [
                'updateProductData' => [
                    'isInventoryManaged' => false,
                ],
                'expectedValue'     => false,
            ],
            'startDate'                            => [
                'updateProductData' => [
                    'startDate' => '2019-12-12T00:00:00.000Z',
                ],
                'expectedValue'     => '2019-12-12T00:00:00.000Z',
            ],
            'endDate'                              => [
                'updateProductData' => [
                    'endDate' => '2016-02-03T00:00:00.000Z',
                ],
                'expectedValue'     => '2016-02-03T00:00:00.000Z',
            ],
            'firstAvailableDate'                   => [
                'updateProductData' => [
                    'firstAvailableDate' => '2019-05-07T00:00:00.000Z',
                ],
                'expectedValue'     => '2019-05-07T00:00:00.000Z',
            ],
            'eolDate'                              => [
                'updateProductData' => [
                    'eolDate' => '2032-11-11T00:00:00.000Z',
                ],
                'expectedValue'     => '2032-11-11T00:00:00.000Z',
            ],
            'status scheduled'                     => [
                'updateProductData' => [
                    'status' => Product::STATUS_SCHEDULED,
                ],
                'expectedValue'     => Product::STATUS_SCHEDULED,
            ],
            'status inactive'                      => [
                'updateProductData' => [
                    'status' => Product::STATUS_INACTIVE,
                ],
                'expectedValue'     => Product::STATUS_INACTIVE,
            ],
            'status active'                        => [
                'updateProductData' => [
                    'status' => Product::STATUS_ACTIVE,
                ],
                'expectedValue'     => Product::STATUS_ACTIVE,
            ],
            'inventoryTreatment allow backorders'  => [
                'updateProductData' => [
                    'inventoryTreatment' => Product::INVENTORY_TREATMENT_ALLOW_BACK_ORDERS,
                ],
                'expectedValue'     => Product::INVENTORY_TREATMENT_ALLOW_BACK_ORDERS,
            ],
            'inventoryTreatment pre order'         => [
                'updateProductData' => [
                    'inventoryTreatment' => Product::INVENTORY_TREATMENT_PRE_ORDER,
                ],
                'expectedValue'     => Product::INVENTORY_TREATMENT_PRE_ORDER,
            ],
            'inventoryTreatment show out of stock' => [
                'updateProductData' => [
                    'inventoryTreatment' => Product::INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK,
                ],
                'expectedValue'     => Product::INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK,
            ],
            'modelType bundle'                     => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_BUNDLE,
                ],
                'expectedValue'     => Product::MODEL_TYPE_BUNDLE,
            ],
            'modelType bundle item'                => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_BUNDLE_ITEM,
                ],
                'expectedValue'     => Product::MODEL_TYPE_BUNDLE_ITEM,
            ],
            'modelType configurable'               => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_CONFIGURABLE,
                ],
                'expectedValue'     => Product::MODEL_TYPE_CONFIGURABLE,
            ],
            'modelType standard'                   => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_STANDARD,
                ],
                'expectedValue'     => Product::MODEL_TYPE_STANDARD,
            ],
            'modelType variant'                    => [
                'updateProductData' => [
                    'modelType' => Product::MODEL_TYPE_VARIANT,
                ],
                'expectedValue'     => Product::MODEL_TYPE_VARIANT,
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
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->code);
        $this->assertEquals($product->getCode(), $product->code);
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
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->code);
        $this->assertEquals($product->getCode(), $product->code);
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
        $this->sdk->getCatalogService()->deleteProduct($product->code, ['requestType' => 'direct']);

        // Assert
        $this->expectException(NotFoundException::class);
        $this->sdk->getCatalogService()->getProduct($product->code);
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
        $this->sdk->getCatalogService()->deleteProduct($product->code);
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $this->expectException(NotFoundException::class);
        $this->sdk->getCatalogService()->getProduct($product->code);
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
        $product = $this->sdk->getCatalogService()->getProduct($productMinimum->code);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMinimum->code,
            ]
        );

        // Assert
        $this->assertEquals($productMinimum->code, $product->getCode());
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
        $product = $this->sdk->getCatalogService()->getProduct($productMinimum->code, ['fields' => 'code']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMinimum->code,
            ]
        );

        // Assert
        $this->assertEquals($productMinimum->code, $product->getCode());
        $this->assertEquals(json_encode(['code' => $productMinimum->code], true), $product->toJson());
    }

    /**
     * @throws Exception
     */
    public function testGetProducts()
    {
        // Arrange
        $productMinimum      = $this->prepareProductMinimum();
        $productMaximum      = $this->prepareProductMaximum();
        $this->sdk->getCatalogService()->addProducts([$productMinimum, $productMaximum], ['requestType' => 'direct']);

        // Act
        $products = $this->sdk->getCatalogService()->getProducts();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [
                $productMaximum->code,
                $productMinimum->code,
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
                $productMaximum->code,
                $productMinimum->code,
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
            'get the second'    => [
                'limit'         => 1,
                'offset'        => 1,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'get the first'     => [
                'limit'         => 1,
                'offset'        => 0,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                ],
            ],
            'get two'           => [
                'limit'         => 2,
                'offset'        => 0,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'limit 1'           => [
                'limit'         => 1,
                'offset'        => null,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                ],
            ],
            'limit 2'           => [
                'limit'         => 2,
                'offset'        => null,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::PRODUCT_CODE,
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'offset 1'          => [
                'limit'         => null,
                'offset'        => 1,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::PRODUCT_CODE_SECOND,
                ],
            ],
            'offset 2'          => [
                'limit'         => null,
                'offset'        => 2,
                'expectedCount' => 0,
                'expectedCodes' => [],
            ],
            'no entities found' => [
                'limit'         => 1,
                'offset'        => 2,
                'expectedCount' => 0,
                'expectedCodes' => [],
            ],
        ];
    }

    /**
     * @return Categories[]
     */
    private function provideCategoryMapping()
    {
        $categoryMapping = new Product\Dto\Categories();
        $categoryMapping->setCode(self::CATEGORY_CODE)
            ->setIsPrimary(true);

        $categoryMapping2 = new Product\Dto\Categories();
        $categoryMapping2->setCode(self::CATEGORY_CODE_SECOND)
            ->setIsPrimary(false);

        return [$categoryMapping, $categoryMapping2];
    }

    private function provideIdentifiers()
    {
        return (new Product\Dto\Identifiers())->setMfgPartNum('someMfgPartNum')
            ->setUpc('Universal-Product-Code')
            ->setEan('European Article Number')
            ->setIsbn('978-3-16-148410-0')
            ->setSku('stock_keeping_unit');
    }

    /**
     * @return VolumePricing[]
     */
    private function provideVolumePricing()
    {
        $volumePricing1 = new Product\Dto\Price\VolumePricing();
        $volumePricing1->setMinQty(5)
            ->setMaxQty(20)
            ->setPrice(84.99)
            ->setSalePrice(83.99)
            ->setUnit('kg')
            ->setPriceType(Product\Dto\Price\VolumePricing::PRICE_TYPE_FIXED);

        $volumePricing2 = new Product\Dto\Price\VolumePricing();
        $volumePricing2->setMinQty(21)
            ->setMaxQty(100)
            ->setPrice(84.99)
            ->setSalePrice(-2)
            ->setUnit('kg')
            ->setPriceType(Product\Dto\Price\VolumePricing::PRICE_TYPE_RELATIVE);

        return [$volumePricing1, $volumePricing2];
    }

    /**
     * @return MapPricing[]
     */
    private function provideMapPricing()
    {
        $mapPricing1 = new Product\Dto\Price\MapPricing();
        $mapPricing1->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(84.49);

        $mapPricing2 = new Product\Dto\Price\MapPricing();
        $mapPricing2->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(84.49);

        return [$mapPricing1, $mapPricing2];
    }

    /**
     * @return Price
     */
    private function providePricing()
    {
        $volumePricing = $this->provideVolumePricing();
        $mapPricing    = $this->provideMapPricing();

        return (new Product\Dto\Price())->setCurrencyCode(Product\Dto\Price::CURRENCY_CODE_USD)
            ->setCost(50)
            ->setPrice(90)
            ->setSalePrice(84.99)
            ->setVolumePricing($volumePricing)
            ->setUnit('kg')
            ->setMsrp(100)
            ->setMinPrice(80)
            ->setMaxPrice(90)
            ->setMapPricing($mapPricing);
    }

    /**
     * @return Properties\SubDisplayGroup
     */
    private function provideSubDisplayGroup()
    {
        return (new Properties\SubDisplayGroup())
            ->add('de-de', 'deutsch')
            ->add('en-en', 'english');
    }

    /**
     * @param Product $product
     *
     * @return Update
     *
     * @throws Exception
     */
    private function prepareProductMaximum($product = null)
    {
        if ($product === null) {
            $product = new Product\Create();
        }

        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, ['requestType' => 'direct']);
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, $sampleCategoryCodes);

        $this->createSampleAttribute();
        $this->createSampleExtras();

        $categories          = $this->provideCategoryMapping();
        $identifiers         = $this->provideIdentifiers();
        $price               = $this->providePricing();
        $properties          = $this->provideProperties();
        $shippingInformation = $this->provideShippingInformation();
        $media               = $this->provideMedia();
        $options             = $this->provideOptions();
        $extras              = $this->provideExtras();
        $name                = new Product\Dto\Name();
        $name->add('en-us', 'Productname in english');
        $name->add('de-de', 'Produktname in deutsch');

        $longName = new Product\Dto\LongName();
        $longName->add('en-us', 'Long Productname in english');
        $longName->add('de-de', 'Long Produktname in deutsch');

        return $product
            ->setName($name)
            ->setLongName($longName)
            ->setShortDescription(new ShortDescription(['en-us' => 'short description', 'de-de' => 'Kurzbeschreibung']))
            ->setLongDescription(new LongDescription(['en-us' => 'long description', 'de-de' => 'Beschreibung']))
            ->setCategories($categories)
            ->setProperties($properties)
            ->setMedia($media)
            ->setOptions($options)
            ->setExtras($extras)
            ->setCode(self::PRODUCT_CODE_SECOND)// required
            ->setParentProductCode('dfsdf7')
            ->setCatalogCode('PNW Retail')// required
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)// required
            ->setIdentifiers($identifiers)
            ->setPrice($price)// required
            ->setFulfillmentMethods(['one method', 'another method'])
            ->setUnit('kg')
            ->setIsSerialized(false)
            ->setStatus(Product\Create::STATUS_ACTIVE)// required
            ->setStartDate('2018-12-01T00:00:00.000Z')
            ->setEndDate('2020-12-01T00:00:00.000Z')
            ->setFirstAvailableDate('2019-06-03T00:00:00.000Z')
            ->setEolDate('2030-01-01T00:00:00.000Z')
            ->setIsInventoryManaged(true)// required
            ->setInventoryTreatment(Product\Create::INVENTORY_TREATMENT_PRE_ORDER)
            ->setShippingInformation($shippingInformation)
            ->setRating(3.5)
            ->setUrl('http://wwww.test.com')
            ->setIsTaxed(true)
            ->setTaxClass('f8c5c2e9')
            ->setMinQty(1)
            ->setMaxQty(100)
            ->setExternalUpdateDate('2019-06-01T00:00:00.000Z');
    }

    /**
     * @return Product\Dto\Properties[]
     */
    private function provideProperties()
    {
        $subDisplayGroup = $this->provideSubDisplayGroup();

        $property1 = new Product\Dto\Properties\Product();
        $property1->setCode('property_code_1')
            ->setName(new Properties\Name(['en-us' => 'property 1 english', 'de-de' => 'property 1 deutsch']))
            ->setValue(new Properties\Value(['stuff' => 'stuff value', 'other stuff' => 'other stuff value']))
            ->setDisplayGroup(Properties::DISPLAY_GROUP_FEATURES)
            ->setSubDisplayGroup($subDisplayGroup);

        $property2 = (new Product\Dto\Properties\Simple())->setCode('property_code_2')
            ->setName(
                new Properties\Name(
                    [
                        'en-us' => 'property 2 english',
                        'de-de' => 'property 2 deutsch',
                    ]
                )
            )
            ->setValue(new Properties\Value())
            ->setDisplayGroup('features')
            ->setSubDisplayGroup($subDisplayGroup);

        return [$property1, $property2];
    }

    /**
     * @return Product\Dto\ShippingInformation
     */
    private function provideShippingInformation()
    {
        return (new Product\Dto\ShippingInformation())
            ->setIsShippedAlone(false)
            ->setHeight(0.5)
            ->setHeightUnit('m')
            ->setWidth(10)
            ->setWidthUnit('cm')
            ->setLength(5)
            ->setLengthUnit('dm')
            ->setWeight(5)
            ->setWeightUnit('kg');
    }

    /**
     * @return MediaList
     */
    private function provideMedia()
    {
        $media1 = new Product\Dto\MediaList\Media();
        $media1->setCode('media_code_1')
               ->setType(Product\Dto\MediaList\Media::TYPE_IMAGE)
               ->setUrl('example.com/media1.jpg')
               ->setAltText('alt text 1')
               ->setSubTitle('Title Media 1')
               ->setSequenceId(0);

        $media2 = new Product\Dto\MediaList\Media();
        $media2->setCode('media_code_2')
               ->setType(Product\Dto\MediaList\Media::TYPE_VIDEO)
               ->setUrl('example.com/media2.mov')
               ->setAltText('alt text 2')
               ->setSubTitle('Title Media 2')
               ->setSequenceId(5);

        $media = new MediaList();
        $media->add('en-us', [$media1, $media2]);

        return $media;
    }

    /**
     * @return array
     */
    private function provideOptions()
    {
        list($value1) = $this->provideOptionsValues();

        $option1 = new Product\Dto\Options();
        $option1->setCode(self::SAMPLE_ATTRIBUTE_CODE)
            ->setValues([$value1]);

        return [$option1];
    }

    /**
     * @return Extras[]
     */
    private function provideExtras()
    {
        list($value1, $value2) = $this->provideExtraValues();
        $extra1 = new Product\Dto\Extras();
        $extra1->setCode(self::SAMPLE_EXTRA_CODE)
            ->setValues([$value1, $value2]);

        $extra2 = new Product\Dto\Extras();
        $extra2->setCode(self::SAMPLE_EXTRA_CODE_2)
            ->setValues([$value2]);

        return [$extra1, $extra2];
    }

    /**
     * @return array
     */
    private function provideOptionsValues()
    {
        $value1 = new Product\Dto\Options\Values();
        $value1->setCode(self::SAMPLE_ATTRIBUTE_VALUE_CODE)
            ->setAdditionalPrice(5);

        return [$value1];
    }

    /**
     * @return array
     */
    private function provideExtraValues()
    {
        $value1 = new Product\Dto\Options\Values();
        $value1->setCode(self::SAMPLE_EXTRA_VALUE_CODE)
            ->setAdditionalPrice(5);
        $value2 = new Product\Dto\Options\Values();
        $value2->setCode(self::SAMPLE_EXTRA_VALUE_CODE_2)
            ->setAdditionalPrice(10);

        return [$value1, $value2];
    }
}
