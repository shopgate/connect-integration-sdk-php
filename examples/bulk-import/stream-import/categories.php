<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

$categories = provideSampleCategories();

try {
    $handler = $sdk->getBulkImportService()->createStreamImport();
    $categoryHandler = $handler->createCategoryFeed(CATALOG_CODE);
    $categoryHandler->add($categories[0]);
    $categoryHandler->add($categories[1]);
    $categoryHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
