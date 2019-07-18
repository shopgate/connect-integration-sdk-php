<?php

require_once('../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;
use \Shopgate\ConnectSdk\Exception\Exception;

$catalogs = [
    'catalogs' => [
        new Base([
            'code'                => 'NARetail',
            'parentCatalogCode'   => 'BANA',
            'name'                => 'North American Wholesale',
            'isDefault'           => true,
            'defaultLocaleCode'   => 'en-us',
            'defaultCurrencyCode' => 'USD',
        ]),
    ]
];

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
