<?php

require_once __DIR__ . '/../../../../../bootstrap.php';

$fulfillmentOrderNumber = '123456';

try {
    $order = $sdk->getOrderService()->getFulfillmentOrder($fulfillmentOrderNumber);
    $fulfillmentItem = $order->getFulfillments()[0];
    $fulfillmentPackage = $fulfillmentItem->getFulfillmentPackages()[0];
    $packageItems = $fulfillmentPackage->getPackageItems();
    
    var_dump($packageItems);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
