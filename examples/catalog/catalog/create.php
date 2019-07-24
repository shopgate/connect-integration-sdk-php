<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;
use \Shopgate\ConnectSdk\Exception\Exception;

$catalogs = provideCatalogs();

try {
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
