<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

try {
    $sdk->getCatalogService()->deleteCategory(CATEGORY_CODE);
    $sdk->getCatalogService()->deleteCategory(CATEGORY_CODE_SECOND);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
