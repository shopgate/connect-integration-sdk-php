<?php

require_once __DIR__ . '/../bootstrap.php';

try {
    $webhooks = $sdk->getWebhooksService()->getWebhooks();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
