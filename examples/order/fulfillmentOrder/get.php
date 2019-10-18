<?php

require_once __DIR__ . '/../bootstrap.php';

$fulfillmentOrderNumber = '123456';

try {
    $order = $sdk->getOrderService()->getFulfillmentOrder($fulfillmentOrderNumber);

    var_dump($order);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
