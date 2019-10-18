<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $response = $sdk->getWebhooksService()->getWebhookToken();
    $token = $response->getWebhookToken();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
