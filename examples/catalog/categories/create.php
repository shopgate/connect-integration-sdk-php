<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * preconditions:
 * - a default catalog exists
 */
$categories = provideSampleCategories();

try {
    $sdk->getCatalogService()->addCategories($categories);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
