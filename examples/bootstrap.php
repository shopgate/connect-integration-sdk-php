<?php

use Shopgate\ConnectSdk\ShopgateSdk;

require dirname(__FILE__) . '/../vendor/autoload.php';

const CATALOG_CODE = 'NARetail';

const CATEGORY_CODE = 'Test';
const CATEGORY_CODE_SECOND = 'Second Test';

const PRODUCT_CODE = 'Test';
const PRODUCT_CODE_SECOND = 'Second Test';

const EXTRA_CODE = 'Test';
const EXTRA_CODE_SECOND = 'Test';
const EXTRA_VALUE_CODE = 'Test';
const EXTRA_VALUE_CODE_SECOND = 'Test';

$config = [
    'merchantCode'  => 'BANA',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx',
    'env'           => 'dev'
];

$sdk = new ShopgateSdk($config);