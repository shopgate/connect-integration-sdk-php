<?php

require_once __DIR__ . '/../../bootstrap.php';

$ordersNumber = '951357';

try {
    $order = $sdk->getOrderService()->getOrder($ordersNumber);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
