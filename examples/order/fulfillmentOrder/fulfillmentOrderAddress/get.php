<?php

require_once __DIR__ . '/../../../bootstrap.php';

$fulfillmentOrderNumber = '123456';

try {
    $order                   = $sdk->getOrderService()->getFulfillmentOrder($fulfillmentOrderNumber);
    $fulfillmentOrderAddress = $order->getFulfillmentOrderAddress();

    var_dump($fulfillmentOrderAddress);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
