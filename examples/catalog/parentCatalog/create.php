<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;
use \Shopgate\ConnectSdk\Exception\Exception;

$parentCatalogs = provideParentCatalogs();

try {
    $sdk->getClient()->doRequest(
        [
            // general
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            'json'        => $parentCatalogs,
            'query'       => [],
            // direct
            'method'      => 'post',
            'service'     => 'catalog',
            'path'        => 'parentCatalogs',
        ]
    );
} catch (Exception $exception) {
    echo $exception->getMessage();
}
