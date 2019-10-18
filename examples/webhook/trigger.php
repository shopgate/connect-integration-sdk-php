<?php

require_once __DIR__ . '/../bootstrap.php';

$code = getCodeOfWebhookToTrigger();

try {
    $sdk->getWebhooksService()->triggerWebhook($code);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
