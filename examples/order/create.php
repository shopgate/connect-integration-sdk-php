<?php

require_once(dirname(__FILE__) . '/../bootstrap.php');

use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Dto\Order\Order;

$config = [
    'merchantCode' => 'EE1',
    'clientId' => 'xxx',
    'clientSecret' => 'xxx'
];

$sgSdk = new ShopgateSdk($config);

// create a new order
$order = new Order\Create([
    'customerId' => 'customer-1',
    'localeCode' => 'en-us',
    'currencyCode' => 'USD',
    'primaryBillToAddressSequenceIndex' => 0,
    'subTotal' => 100,
    'total' => 100,
    'submitDate' => date('c'),
]);
$order->setAddressSequences([
    new Order\Dto\Address([
        'type' => Order::ADDRESS_TYPE_BILLING,
        'firstName' => 'Jane',
        'lastName' => 'Doe',
    ])
]);
$lineItem = new Order\Dto\LineItem([
    'code' => 'line-item-one',
    'quantity' => 1,
    'fulfillmentMethod' => Order::LINE_ITEM_FULFILLMENT_METHOD_DIRECT_SHIP,
    'shipToAddressSequenceIndex' => 0,
    'product' => new Order\Dto\LineItem\Product([
        'code' => 'product-one',
        'name' => 'Product One',
        'image' => 'https://mywebsite.com/images/product-one.jpg',
        'price' => 100,
        'currencyCode' => 'USD'
    ])
]);
$order->setLineItems([$lineItem]);
try {
    $sgSdk->getOrderService()->addOrders([$order]);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
