<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

try {
    $products = provideSampleProducts();

    $handler = $sdk->getBulkImportService()->createFileImport();
    $productHandler = $handler->createProductFeed(CATALOG_CODE);
    $productHandler->add($products[0]);
    $productHandler->add($products[1]);
    $productHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
