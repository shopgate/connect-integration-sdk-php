<?php

require_once('../../bootstrap.php');

use \Shopgate\ConnectSdk\Dto\Catalog\Product;

$products = provideSampleProducts();

try {
    $handler = $sdk->getBulkImportService()->createFileImport();
    $productHandler = $handler->createProductFeed(CATALOG_CODE);
    $productHandler->add($products[0]);
    $productHandler->add($products[1]);
    $productHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}


/**
 * @return Product\Create[]
 */
function provideSampleProducts()
{
    $products = [];

    $product = new Product\Create();
    $product->setName(new Product\Dto\Name(['en-us' => 'Product Name']))
            ->setCode(PRODUCT_CODE)
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setIsInventoryManaged(true);

    $products[] = $product;

    $product = new Product\Create();
    $product->setName(new Product\Dto\Name(['en-us' => 'Product Name Second']))
            ->setCode(PRODUCT_CODE_SECOND)
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setIsInventoryManaged(true);

    $products[] = $product;

    return $products;
}
