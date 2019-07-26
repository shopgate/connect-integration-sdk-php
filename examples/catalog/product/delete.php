<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

try {
    $sdk->getCatalogService()->deleteProduct(PRODUCT_CODE);
    $sdk->getCatalogService()->deleteProduct(PRODUCT_CODE_SECOND);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
