<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;

$inventories = provideSampleDeleteInventories();

try {
    $sdk->getCatalogService()->deleteInventories($inventories, [
        'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT
    ]);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
