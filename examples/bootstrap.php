<?php

use Shopgate\ConnectSdk\ShopgateSdk;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    if (file_exists(__DIR__ . '/../../../autoload.php')) {
        require __DIR__ . '/../../../autoload.php';
    }
}

require __DIR__ . '/fixtures.php';

if (file_exists(__DIR__ . '/config.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once __DIR__ . '/config.php';
} else {
    $config = [
        'merchantCode'  => 'xxx',
        'clientId'      => 'xxx',
        'clientSecret'  => 'xxx',
        'username'      => 'xxx',
        'password'      => 'xxx'
    ];
}

$sdk = new ShopgateSdk($config);
