<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    // tests webhook by making it trigger
    $sdk->getWebhooksService()->triggerWebhook('some-webhook-code');
} catch (Exception $exception) {
    echo $exception->getMessage();
}
