<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

$attributes = provideSampleAttributes();

try {
    $sdk->getCatalogService()->addAttributes($attributes);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
