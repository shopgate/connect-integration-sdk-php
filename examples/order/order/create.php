<?php

require_once __DIR__ . '/../../bootstrap.php';

try {
    $orders = provideSampleOrders();

    $sdk->getOrderService()->addOrders($orders);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
