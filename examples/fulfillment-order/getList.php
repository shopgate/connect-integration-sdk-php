<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $orders = $sdk->getOrderService()->getFulfillmentOrders();

    var_dump($orders);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
