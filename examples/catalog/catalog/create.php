<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;
use \Shopgate\ConnectSdk\Exception\Exception;

/**
 * preconditions:
 * - parentCatalog PARENT_CATALOG_CODE exists
 */

try {
    $catalogs = provideCatalogs();

    $sdk->getClient()->doRequest(
        [
            // general
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            'json'        => $catalogs,
            'query'       => [],
            // direct
            'method'      => 'post',
            'service'     => 'catalog',
            'path'        => 'catalogs',
        ]
    );
} catch (Exception $exception) {
    echo $exception->getMessage();
}
