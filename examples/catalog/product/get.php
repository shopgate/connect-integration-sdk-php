<?php

use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\MapPricing;

date_default_timezone_set('Europe/Berlin');

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
