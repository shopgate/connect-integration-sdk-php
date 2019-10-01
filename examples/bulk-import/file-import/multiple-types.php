<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

try {
    $inventory = provideSampleInventories();
    $attributes = provideSampleAttributes();
    $categories = provideSampleCategories();
    $products = provideSampleProducts();

    $handler = $sdk->getBulkImportService()->createFileImport();
    $attributeHandler = $handler->createAttributeFeed(CATALOG_CODE);
    $attributeHandler->add($attributes[0]);
    $attributeHandler->add($attributes[1]);
    $attributeHandler->end();
    $productHandler = $handler->createProductFeed(CATALOG_CODE);
    $productHandler->add($products[0]);
    $productHandler->add($products[1]);
    $productHandler->end();
    $categoryHandler = $handler->createCategoryFeed(CATALOG_CODE);
    $categoryHandler->add($categories[0]);
    $categoryHandler->add($categories[1]);
    $categoryHandler->end();
    $inventoryHandler = $handler->createInventoryFeed(CATALOG_CODE);
    $inventoryHandler->add($inventory[0]);
    $inventoryHandler->add($inventory[1]);
    $inventoryHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
