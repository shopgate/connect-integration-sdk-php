<?php

require_once __DIR__ . '/../../../bootstrap.php';

$fulfillmentOrderNumber = '123456';

try {
    $order            = $sdk->getOrderService()->getFulfillmentOrder($fulfillmentOrderNumber);
    $fulfillmentItems = $order->getFulfillments();

    var_dump($fulfillmentItems);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
