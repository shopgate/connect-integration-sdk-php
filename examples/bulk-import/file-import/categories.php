<?php

require_once('../../bootstrap.php');

use \Shopgate\ConnectSdk\Dto\Catalog\Category;

$categories = provideSampleCreateCategories();

try {
    $handler         = $sdk->getBulkImportService()->createFileImport();
    $categoryHandler = $handler->createCategoryFeed(CATALOG_CODE);
    $categoryHandler->add($categories[0]);
    $categoryHandler->add($categories[1]);
    $categoryHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}


/**
 * @return Category\Create[]
 */
function provideSampleCreateCategories()
{
    $categories = [];

    $category = new Category\Create();
    $category->setCode(CATEGORY_CODE)
             ->setName(new Category\Dto\Name(['en-us' => 'Test Category 1']))
             ->setSequenceId(1);
    $category->setDescription(new Category\Dto\Description(['en-us' => 'test description']));
    $category->setUrl(new Category\Dto\Url(['en-us' => 'http://www.example.com']));
    $category->setImage(new Category\Dto\Image(['en-us' => 'http://www.example.com/image.png']));
    $category->setStatus(Category::STATUS_ACTIVE);

    $categories[] = $category;

    $category = new Category\Create();
    $category->setCode(CATEGORY_CODE)
             ->setName(new Category\Dto\Name(['en-us' => 'Test Category 2']))
             ->setSequenceId(1);
    $category->setDescription(new Category\Dto\Description(['en-us' => 'test description 2']));
    $category->setUrl(new Category\Dto\Url(['en-us' => 'http://www.example.com/"']));
    $category->setImage(new Category\Dto\Image(['en-us' => 'http://www.example.com/image2.png']));
    $category->setStatus(Category::STATUS_ACTIVE);

    $categories[] = $category;

    return $categories;
}