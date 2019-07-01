<?php


namespace Shopgate\ConnectSdk\Tests\Integration\Http;

use Exception;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest;

class BulkImportTest extends CatalogTest
{
    /**
     * Can be replaced with better integration tests. This is more proof of concept for import service
     *
     * @throws Exception
     */
    public function testBulkImport() {
        // Arrange
        $categoryPayload1 = new Category\Create();
        $name1            = new Category\Dto\Name(['en-us' => 'Denim Pants']);
        $categoryPayload1->setCode('pants')->setName($name1)->setSequenceId(1);


        $categoryPayload2 = new Category\Create();
        $name2            = new Category\Dto\Name(['en-us' => 'Denim Shirts']);
        $categoryPayload2->setCode('shirts')->setName($name2)->setSequenceId(1);

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $categoryHandler = $handler->createCategoryFeed('8000');
        $categoryHandler->add($categoryPayload1);
        $categoryHandler->add($categoryPayload2);
        $categoryHandler->end();
        $handler->trigger();
    }
}
