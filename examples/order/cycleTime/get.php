<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $response = $sdk->getOrderService()->getCycleTimes('today');
    $cycleTimes = $response->getCycleTimes();
    $firstType = $cycleTimes[0]->getType();
    $firstTime = $cycleTimes[0]->getTime();
    $firstDifference = $cycleTimes[0]->getDifference();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
