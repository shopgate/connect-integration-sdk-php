<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

$categories = provideSampleCategories();

try {
    $sdk->getCatalogService()->addCategories($categories);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
