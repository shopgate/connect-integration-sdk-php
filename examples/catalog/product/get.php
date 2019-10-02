<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

/**
* preconditions:
* - a default catalog exists
*/

try {
    $products = $sdk->getCatalogService()->getProducts();

    var_dump($products);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
