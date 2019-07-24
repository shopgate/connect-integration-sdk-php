<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;

/**
 * preconditions:
 * - location LOCATION_CODE exists
 * - product PRODUCT_CODE and PRODUCT_CODE_SECOND exists
 * - catalog CATALOG_CODE exists
 */
$inventory = provideSampleInventories();

try {
    $sdk->getCatalogService()->addInventories($inventory, [
        'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT
    ]);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
