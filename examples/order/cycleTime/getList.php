<?php


require_once __DIR__ . '/../bootstrap.php';

try {
    $response = $sdk->getOrderService()->getCycleTimes('today');
    $cycleTimes = $response->getCycleTimes();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
