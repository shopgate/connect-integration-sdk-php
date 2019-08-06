<?php

require_once(dirname(__FILE__) . '/../bootstrap.php');

$ordersNumber = '951357';

try {
    $order = $sgSdk->getOrderService()->getOrder($ordersNumber);
} catch (Exception $exception) {
    echo $exception->getMessage();
}

