<?php

/**
 * Copyright Shopgate Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Shopgate Inc, 804 Congress Ave, Austin, Texas 78701 <interfaces@shopgate.com>
 * @copyright Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Tests\Integration;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\DTO\Catalog\Category;
use Shopgate\ConnectSdk\DTO\Catalog\Category\Get;
use Shopgate\ConnectSdk\DTO\Catalog\Category\Name;
use Shopgate\ConnectSdk\ShopgateSdk;

class ShopgateSdkTest extends TestCase
{
    const CATEGORY_CODE = 'integration-test';
    /** @var array */
    protected $sdkConfig = [];

    /**Ø
     * Main setup before any tests are ran, runs once
     */
    public static function setUpBeforeClass()
    {
        $env = Dotenv::create(__DIR__);
        $env->load();
        $env->required(['clientId', 'clientSecret', 'merchantCode', 'env']);
        //todo-sg: delete all previously (possibly) created categories
    }

    /**
     * Runs before every test
     */
    public function setUp()
    {
        $this->sdkConfig = [
            'clientId'     => getenv('clientId'),
            'clientSecret' => getenv('clientSecret'),
            'merchantCode' => getenv('merchantCode'),
            'env'          => getenv('env')
        ];
    }

    /**
     * Testing create, read, update, delete functionality
     */
    public function testCategoryCRUD()
    {
        $sdk = new ShopgateSdk($this->sdkConfig);

        // check no category exists
        $categories = $this->getCategories($sdk);
        $this->assertCount(0, $categories);

        // create 2 categories
        $payload = new Category\Create();
        $name    = new Name(['en-us' => 'Denim Jeans']);
        $payload->setCode(self::CATEGORY_CODE)->setName($name)->setSequenceId(1);

        $payload2 = (new Category\Create())
            ->setCode(self::CATEGORY_CODE . '_2')
            ->setName(new Name(['en-us' => 'Denim Skirts']))
            ->setSequenceId(1);
        $response = $sdk->catalog->addCategories([$payload, $payload2]);
        $this->assertEquals(204, $response->getStatusCode());
        $categories2 = $this->getCategories($sdk);
        $this->assertCount(2, $categories2);

        // update category
        $payload3 = new Category\Update(['name' => new Name(['en-us' => 'Cloth Jeans'])]);
        $sdk->catalog->updateCategory(self::CATEGORY_CODE, $payload3);
        $categories3 = $this->getCategories($sdk);
        $this->assertEquals('Cloth Jeans', array_shift($categories3)->getCode());

        // delete categories
        $sdk->catalog->deleteCategory(self::CATEGORY_CODE);
        $sdk->catalog->deleteCategory(self::CATEGORY_CODE . '_2');
        $categories4 = $this->getCategories($sdk);
        $this->assertCount(0, $categories4);
    }

    /**
     * @param ShopgateSdk $sdk
     *
     * @return Get[]
     */
    private function getCategories(ShopgateSdk $sdk)
    {
        // todo-sg: adjust to filter for IN => CATEGORY_CODE_%
        return $sdk->catalog->getCategories(['filters' => ['code' => self::CATEGORY_CODE]])->getCategories();
    }
}
