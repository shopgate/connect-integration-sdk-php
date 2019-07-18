<?php


namespace Shopgate\ConnectSdk\Tests\Integration\Http;

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest;

class BulkImportTest extends CatalogTest
{
    const SLEEP_TIME_AFTER_BULK = 10;

    /**
     * @throws Exception
     */
    public function testMaximumBulkFileImport()
    {
        // Arrange
        $categories = $this->provideSampleCategories();
        $extras = $this->provideSampleExtras();
        $products[] = $this->prepareProductMinimum();
        $products[] = $this->prepareProductMaximum(null, $categories, $extras, []);

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $categoryHandler = $handler->createCategoryFeed(self::SAMPLE_CATALOG_CODE);
        $categoryHandler->add($categories[0]);
        $categoryHandler->add($categories[1]);
        $categoryHandler->end();
        $attributeHandler = $handler->createAttributeFeed(self::SAMPLE_CATALOG_CODE);
        $attributeHandler->add($extras[0]);
        $attributeHandler->add($extras[1]);
        $attributeHandler->end();
        $productHandler = $handler->createProductFeed(self::SAMPLE_CATALOG_CODE);
        $productHandler->add($products[0]);
        $productHandler->add($products[1]);
        $productHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [
            $categories[0]->code,
            $categories[1]->code
        ]);
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [
            $products[0]->code,
            $products[1]->code
        ]);
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_ATTRIBUTE, [
            $extras[0]->code,
            $extras[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK + 5);

        // Assert
        $availableCategories = $this->sdk->getCatalogService()->getCategories();
        $this->assertCount(2, $availableCategories->getCategories());
        $availableProducts = $this->sdk->getCatalogService()->getProducts();
        $this->assertCount(2, $availableProducts->getProducts());
        $availableAttributes = $this->sdk->getCatalogService()->getAttributes();
        $this->assertCount(2, $availableAttributes->getAttributes());
    }


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
            $categories[0]->code,
            $categories[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK);

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
            $categories[0]->code,
            $categories[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK);

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
            $products[0]->code,
            $products[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK);

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
            $products[0]->code,
            $products[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK);

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
        $attributeHandler = $handler->createAttributeFeed(self::SAMPLE_CATALOG_CODE);
        $attributeHandler->add($attributes[0]);
        $attributeHandler->add($attributes[1]);
        $attributeHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_ATTRIBUTE, [
            $attributes[0]->code,
            $attributes[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableAttributes = $this->sdk->getCatalogService()->getAttributes();
        $this->assertCount(2, $availableAttributes->getAttributes());
    }

    /**
     * @throws Exception
     */
    public function testAttributeStreamBulkImport()
    {
        // Arrange
        $attributes = $this->provideSampleExtras();

        // Act
        $handler = $this->sdk->getBulkImportService()->createStreamImport();
        $attributeHandler = $handler->createAttributeFeed(self::SAMPLE_CATALOG_CODE);
        $attributeHandler->add($attributes[0]);
        $attributeHandler->add($attributes[1]);
        $attributeHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_ATTRIBUTE, [
            $attributes[0]->code,
            $attributes[1]->code
        ]);

        sleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableAttributes = $this->sdk->getCatalogService()->getAttributes();
        $this->assertCount(2, $availableAttributes->getAttributes());
    }
}
