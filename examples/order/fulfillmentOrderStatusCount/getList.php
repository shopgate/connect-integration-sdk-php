<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $response = $sdk->getOrderService()->getFulfillmentOrderStatusCount();
    $orderStatusCount = $response->getOrderStatusCount();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
