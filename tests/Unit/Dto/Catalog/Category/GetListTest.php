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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Category;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Get;
use Shopgate\ConnectSdk\Dto\Catalog\Category\GetList;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\Exception;
use stdClass;

class GetListTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     *
     * @throws Exception
     */
    public function testCategoryDto()
    {
        $entry = [
            'meta' => [
                'limit' => 1
            ],
            'categories' => [
                ['status' => Category::STATUS_ACTIVE],
                ['code' => 'la2'],
                [
                    'parentCategoryCode' => 'pCode',
                    'catalogCode' => 'catCode',
                    'image' => 'http://img.com',
                    'url' => 'http://url.com',
                    'name' => 'someName',
                    'description' => 'someDesc',
                    'externalUpdateDate' => '2019-12-31',
                    'status' => Category::STATUS_INACTIVE
                ]
            ]
        ];
        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(1, $getList->getMeta()->getLimit());

        $categories = $getList->getCategories();
        $this->assertCount(3, $categories);
        $this->assertTrue(is_array($categories));
        $this->assertInstanceOf(Get::class, $categories[0]);
        $this->assertInstanceOf(Get::class, $categories[1]);
        $this->assertInstanceOf(Get::class, $categories[2]);
        $this->assertEquals(Category::STATUS_ACTIVE, $categories[0]->getStatus());
        $this->assertEquals('la2', $categories[1]->getCode());
        $this->assertEquals('pCode', $categories[2]->getParentCategoryCode());
        $this->assertEquals('catCode', $categories[2]->getCatalogCode());
        $this->assertEquals('http://img.com', $categories[2]->getImage());
        $this->assertEquals('http://url.com', $categories[2]->getUrl());
        $this->assertEquals('someName', $categories[2]->getName());
        $this->assertEquals('someDesc', $categories[2]->getDescription());
        $this->assertEquals('2019-12-31', $categories[2]->getExternalUpdateDate());
        $this->assertEquals(Category::STATUS_INACTIVE, $categories[2]->getStatus());
    }

    /**
     * There should be no typecasting for Get/GetList classes
     *
     * @throws Exception
     */
    public function testInvalidCategoryDto()
    {
        $entry = [
            'meta' => [
                'limit' => '1'
            ],
            'categories' => [
                [
                    'status' => 0,
                    'code' => 23.4,
                    'parentCategoryCode' => 16,
                    'catalogCode' => false,
                    'image' => true,
                    'name' => ['test' => 't'],
                    'description' => [],
                    'externalUpdateDate' => new stdClass(),
                ]
            ]
        ];

        $getList = new GetList($entry);
        $limit = $getList->getMeta()->getLimit();
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertInternalType('string', $limit);
        $this->assertEquals('1', $limit);
        $categories = $getList->getCategories()[0];
        $this->assertEquals(0, $categories->getStatus());
        $this->assertEquals(23.4, $categories->getCode());
        $this->assertEquals(16, $categories->getParentCategoryCode());
        $this->assertEquals(false, $categories->getCatalogCode());
        $this->assertEquals(true, $categories->getImage());
        $this->assertEquals(['test' => 't'], $categories->getName()->toArray());
        $this->assertEquals([], $categories->getDescription());
        $this->assertEquals([], $categories->getExternalUpdateDate());
        $this->assertNull($categories->getUrl());
    }
}
