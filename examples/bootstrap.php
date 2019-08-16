<?php

use Shopgate\ConnectSdk\ShopgateSdk;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/fixtures.php';

if (file_exists(__DIR__ . '/config.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once __DIR__ . '/config.php';
} else {
    $config = [
        'merchantCode'  => 'xxx',
        'clientId'      => 'xxx',
        'clientSecret'  => 'xxx',
    ];
}

$sdk = new ShopgateSdk($config);
