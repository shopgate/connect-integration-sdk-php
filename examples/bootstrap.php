<?php

use Shopgate\ConnectSdk\ShopgateSdk;

require dirname(__FILE__) . '/../vendor/autoload.php';
require dirname(__FILE__) . '/fixtures.php';

if (file_exists(dirname(__FILE__) . '/config.php')) {
    require_once dirname(__FILE__) . '/config.php';
} else {
    $config = [
        'merchantCode'  => 'xxx',
        'clientId'      => 'xxx',
        'clientSecret'  => 'xxx',
    ];
}

$sdk = new ShopgateSdk($config);
