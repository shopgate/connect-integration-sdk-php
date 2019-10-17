<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $orderBreakdown = $sdk->getOrderService()->getFulfillmentOrderBreakdown('today');
} catch (Exception $exception) {
    echo $exception->getMessage();
}
