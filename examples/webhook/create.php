<?php

require_once __DIR__ . '/../bootstrap.php';

/** @noinspection PhpUnhandledExceptionInspection */
$webhooks = provideSampleWebhooks();

try {
    $sdk->getWebhooksService()->addWebhooks($webhooks);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
