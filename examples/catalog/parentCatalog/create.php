<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use Shopgate\ConnectSdk\Exception\Exception;

try {
    $parentCatalogs = provideParentCatalogs();

    $sdk->getCatalogService()->addParentCatalogs($parentCatalogs);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
