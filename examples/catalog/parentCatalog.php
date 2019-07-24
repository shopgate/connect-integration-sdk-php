<?php

require_once('../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;
use \Shopgate\ConnectSdk\Exception\Exception;

$parentCatalogs = [
    'parentCatalogs' => [
        new Base([
            'code'                => 'BANA',
            'name'                => 'Team Banana Parent Catalog',
            'isDefault'           => true,
            'defaultLocaleCode'   => 'en-us',
            'defaultCurrencyCode' => 'USD',
        ])
    ]
];

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
