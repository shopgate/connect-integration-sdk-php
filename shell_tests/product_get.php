<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
require '../vendor/autoload.php';

require_once('./inc/config.php');

$client = new \Shopgate\ConnectSdk\ShopgateSdk($config);

$product = $client->catalog->getProduct('wbg_test_3', '', true);

echo $product->toJson(1);