<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

/**
* preconditions:
* - a default catalog exists
*/

try {
    $products = provideSampleProducts();

    $sdk->getCatalogService()->addProducts($products);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
