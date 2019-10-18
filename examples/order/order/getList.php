<?php

require_once __DIR__ . '/../bootstrap.php';

$externalOrderId = '9514-7253-8521-9456';

try {
    // get orders 10 through 20 for specific order
    $orders = $sdk->getOrderService()->getOrders([
        'offset' => 9,
        'limit' => 10,
        'filters' => ['externalCode' => $externalOrderId]
    ]);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
