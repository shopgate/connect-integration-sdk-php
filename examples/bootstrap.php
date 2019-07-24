<?php

use Shopgate\ConnectSdk\ShopgateSdk;

require dirname(__FILE__) . '/../vendor/autoload.php';
require dirname(__FILE__) . '/fixtures.php';

$config = [
    'merchantCode'  => 'BANA',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx',
    'env'           => 'dev'
];

$sdk = new ShopgateSdk($config);
