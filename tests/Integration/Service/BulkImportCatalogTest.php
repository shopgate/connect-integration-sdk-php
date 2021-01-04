<?php


namespace Shopgate\ConnectSdk\Tests\Integration\Service;

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CatalogUtility;

class BulkImportCatalogTest extends CatalogUtility
{
    const SLEEP_TIME_AFTER_BULK = 12000000;
    const LOCATION_CODE = 'WHS1';

    /**
     * @throws Exception
     */
    public function testCategoryBulkFileImport()
    {
        // Arrange
        $categories = $this->provideSampleCategories();

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $categoryHandler = $handler->createCategoryFeed(self::SAMPLE_CATALOG_CODE);
        $categoryHandler->add($categories[0]);
        $categoryHandler->add($categories[1]);
        $categoryHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [
            $categories[0]->getCode(),
            $categories[1]->getCode()
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableCategories = $this->sdk->getCatalogService()->getCategories();
        $this->assertCount(2, $availableCategories->getCategories());
    }

    /**
     * @throws Exception
     */
    public function testCategoryStreamBulkImport()
    {
        // Arrange
        $categories = $this->provideSampleCategories();

        // Act
        $handler = $this->sdk->getBulkImportService()->createStreamImport();
        $categoryHandler = $handler->createCategoryFeed(self::SAMPLE_CATALOG_CODE);
        $categoryHandler->add($categories[0]);
        $categoryHandler->add($categories[1]);
        $categoryHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [
            $categories[0]->getCode(),
            $categories[1]->getCode()
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableCategories = $this->sdk->getCatalogService()->getCategories();
        $this->assertCount(2, $availableCategories->getCategories());
    }

    /**
     * @throws Exception
     */
    public function testProductBulkFileImport()
    {
        // Arrange
        $products[] = $this->prepareProductMinimum();
        $products[] = $this->prepareProductMaximum();

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $productHandler = $handler->createProductFeed(self::SAMPLE_CATALOG_CODE);
        $productHandler->add($products[0]);
        $productHandler->add($products[1]);
        $productHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [
            $products[0]->getCode(),
            $products[1]->getCode()
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableProducts = $this->sdk->getCatalogService()->getProducts();
        $this->assertCount(2, $availableProducts->getProducts());
    }

    /**
     * @throws Exception
     */
    public function testProductStreamBulkImport()
    {
        // Arrange
        $products[] = $this->prepareProductMinimum();
        $products[] = $this->prepareProductMaximum();

        // Act
        $handler = $this->sdk->getBulkImportService()->createStreamImport();
        $productHandler = $handler->createProductFeed(self::SAMPLE_CATALOG_CODE);
        $productHandler->add($products[0]);
        $productHandler->add($products[1]);
        $productHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [
            $products[0]->getCode(),
            $products[1]->getCode()
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableProducts = $this->sdk->getCatalogService()->getProducts();
        $this->assertCount(2, $availableProducts->getProducts());
    }

    /**
     * @throws Exception
     */
    public function testProductAttributeBulkFileImport()
    {
        // Arrange
        $attributes = $this->provideSampleExtras();

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $attributeHandler = $handler->createAttributeFeed();
        $attributeHandler->add($attributes[0]);
        $attributeHandler->add($attributes[1]);
        $attributeHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_ATTRIBUTE, [
            $attributes[0]->getCode(),
            $attributes[1]->getCode()
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableAttributes = $this->sdk->getCatalogService()->getAttributes();
        $this->assertCount(2, $availableAttributes->getAttributes());
    }

    /**
     * @throws Exception
     */
    public function testProductAttributeStreamBulkImport()
    {
        // Arrange
        $attributes = $this->provideSampleExtras();

        // Act
        $handler = $this->sdk->getBulkImportService()->createStreamImport();
        $attributeHandler = $handler->createAttributeFeed();
        $attributeHandler->add($attributes[0]);
        $attributeHandler->add($attributes[1]);
        $attributeHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_ATTRIBUTE, [
            $attributes[0]->getCode(),
            $attributes[1]->getCode()
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableAttributes = $this->sdk->getCatalogService()->getAttributes();
        $this->assertCount(2, $availableAttributes->getAttributes());
    }

    /**
     * @throws Exception
     */
    public function testInventoryBulkFileImport()
    {
        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [self::PRODUCT_CODE]
        );
        $this->deleteEntitiesAfterTestRun(
            self::LOCATION_SERVICE,
            self::METHOD_DELETE_LOCATION,
            [self::LOCATION_CODE]
        );

        // Arrange
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $this->createLocation(self::LOCATION_CODE);
        $inventories = $this->provideSampleInventories(2, $product->getCode());

        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_INVENTORIES,
            $this->getDeleteInventories($inventories)
        );

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $inventoryHandler = $handler->createInventoryFeed();
        $inventoryHandler->add($inventories[0]);
        $inventoryHandler->add($inventories[1]);
        $inventoryHandler->end();
        $handler->trigger();

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->getCode(), ['fields' => 'inventories']);
        $this->assertCount(2, $product->getInventories());
    }

    /**
     * @throws Exception
     */
    public function testInventoryStreamBulkImport()
    {
        // Arrange
        $product = $this->prepareProductMinimum();
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);
        $this->createLocation(self::LOCATION_CODE);
        $inventories = $this->provideSampleInventories(2, $product->getCode());

        // Act
        $handler = $this->sdk->getBulkImportService()->createStreamImport();
        $inventoryHandler = $handler->createInventoryFeed();
        $inventoryHandler->add($inventories[0]);
        $inventoryHandler->add($inventories[1]);
        $inventoryHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_PRODUCT,
            [$product->getCode()]
        );
        $this->deleteEntitiesAfterTestRun(
            self::LOCATION_SERVICE,
            self::METHOD_DELETE_LOCATION,
            [self::LOCATION_CODE]
        );
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_INVENTORIES,
            $this->getDeleteInventories($inventories)
        );

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->getCode(), ['fields' => 'inventories']);
        $this->assertCount(2, $product->getInventories());
    }

    /**
     * @depends testProductBulkFileImport
     * @depends testCategoryBulkFileImport
     * @depends testProductAttributeStreamBulkImport
     * @depends testInventoryBulkFileImport
     *
     * @throws Exception
     */
    public function testMaximumBulkFileImport()
    {
        // Arrange
        $categories = $this->provideSampleCategories();
        $extras = $this->provideSampleExtras();
        $products[] = $this->prepareProductMinimum();
        $products[] = $this->prepareProductMaximum(null, $categories, $extras, []);
        $inventories =
            array_merge(
                $this->provideSampleInventories(2, $products[0]->getCode()),
                $this->provideSampleInventories(1, $products[1]->getCode())
            );
        $this->createLocation(self::LOCATION_CODE);

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $categoryHandler = $handler->createCategoryFeed(self::SAMPLE_CATALOG_CODE);
        $categoryHandler->add($categories[0]);
        $categoryHandler->add($categories[1]);
        $categoryHandler->end();
        $attributeHandler = $handler->createAttributeFeed();
        $attributeHandler->add($extras[0]);
        $attributeHandler->add($extras[1]);
        $attributeHandler->end();
        $productHandler = $handler->createProductFeed(self::SAMPLE_CATALOG_CODE);
        $productHandler->add($products[0]);
        $productHandler->add($products[1]);
        $productHandler->end();
        $inventoryHandler = $handler->createInventoryFeed();
        $inventoryHandler->add($inventories[0]);
        $inventoryHandler->add($inventories[1]);
        $inventoryHandler->add($inventories[2]);
        $inventoryHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [
            $categories[0]->getCode(),
            $categories[1]->getCode()
        ]);
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [
            $products[0]->getCode(),
            $products[1]->getCode()
        ]);
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_ATTRIBUTE, [
            $extras[0]->getCode(),
            $extras[1]->getCode()
        ]);
        $this->deleteEntitiesAfterTestRun(
            self::LOCATION_SERVICE,
            self::METHOD_DELETE_LOCATION,
            [self::LOCATION_CODE]
        );
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_INVENTORIES,
            $this->getDeleteInventories($inventories)
        );

        usleep(self::SLEEP_TIME_AFTER_BULK + 5000000);

        // Assert
        $availableCategories = $this->sdk->getCatalogService()->getCategories();
        $this->assertCount(2, $availableCategories->getCategories());
        $availableProducts = $this->sdk->getCatalogService()->getProducts();
        $this->assertCount(2, $availableProducts->getProducts());
        $availableAttributes = $this->sdk->getCatalogService()->getAttributes();
        $this->assertCount(2, $availableAttributes->getAttributes());
        $product = $this->sdk->getCatalogService()->getProduct($products[0]->getCode(), ['fields' => 'inventories']);
        $this->assertCount(2, $product->getInventories());
        $product = $this->sdk->getCatalogService()->getProduct($products[1]->getCode(), ['fields' => 'inventories']);
        $this->assertCount(1, $product->getInventories());
    }
}
