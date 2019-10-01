<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

try {
    $categories = provideSampleCategories();

    $handler = $sdk->getBulkImportService()->createFileImport();
    $categoryHandler = $handler->createCategoryFeed(CATALOG_CODE);
    $categoryHandler->add($categories[0]);
    $categoryHandler->add($categories[1]);
    $categoryHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
