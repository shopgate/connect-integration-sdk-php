<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

$attributes = provideSampleAttributes();

try {
    $handler = $sdk->getBulkImportService()->createStreamImport();
    $attributeHandler = $handler->createAttributeFeed();
    $attributeHandler->add($attributes[0]);
    $attributeHandler->add($attributes[1]);
    $attributeHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
