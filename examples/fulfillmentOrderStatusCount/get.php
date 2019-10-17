<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $response = $sdk->getOrderService()->getFulfillmentOrderStatusCount();
    $orderStatusCount = $response->getOrderStatusCount();
    $firstStatus = $orderStatusCount[0]->getStatus();
    $firstCount = $orderStatusCount[0]->getCount();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
