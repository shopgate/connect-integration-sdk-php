<?php

require_once __DIR__ . '/../bootstrap.php';

$orders = provideSampleOrders();

try {
    $sdk->getOrderService()->addOrders($orders);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
