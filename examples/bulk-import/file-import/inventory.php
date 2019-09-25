<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * preconditions:
 * - location LOCATION_CODE exists
 * - product PRODUCT_CODE and PRODUCT_CODE_SECOND exists
 * - catalog CATALOG_CODE exists
 */

try {
    $inventory = provideSampleInventories();

    $handler = $sdk->getBulkImportService()->createFileImport();
    $inventoryHandler = $handler->createInventoryFeed(CATALOG_CODE);
    $inventoryHandler->add($inventory[0]);
    $inventoryHandler->add($inventory[1]);
    $inventoryHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
