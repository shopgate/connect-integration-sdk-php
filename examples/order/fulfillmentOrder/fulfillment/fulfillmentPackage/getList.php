<?php

require_once __DIR__ . '/../../../../bootstrap.php';

$fulfillmentOrderNumber = '123456';

try {
    $order               = $sdk->getOrderService()->getFulfillmentOrder($fulfillmentOrderNumber);
    $fulfillmentItem     = $order->getFulfillments()[0];
    $fulfillmentPackages = $fulfillmentItem->getFulfillmentPackages();

    var_dump($fulfillmentPackages);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
