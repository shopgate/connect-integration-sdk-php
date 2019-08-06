<?php

require_once(dirname(__FILE__) . '/../bootstrap.php');

$customerId = '9514-7253-8521-9456';

try {
    // get orders 10 through 20 for specific customer
    $orders = $sgSdk->getOrderService()->getOrders([
        'offset' => 9,
        'limit' => 10,
        'filters' => ['externalCode' => $customerId]
    ]);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
