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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Product;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Simple;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Get;
use Shopgate\ConnectSdk\Dto\Catalog\Product\GetList;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\Exception;

class GetListTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     *
     * @throws Exception
     */
    public function testGetListDto()
    {
        $entry = [
            'meta' => [
                'limit' => 1
            ],
            'products' => [
                ['code' => 'la'],
                ['code' => 'la2']
            ]
        ];
        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(1, $getList->getMeta()->getLimit());

        $products = $getList->getProducts();
        $this->assertCount(2, $products);
        $this->assertInstanceOf(Get::class, $products[0]);
        $this->assertInstanceOf(Get::class, $products[1]);
        $this->assertEquals('la', $products[0]->getCode());
        $this->assertEquals('la2', $products[1]->getCode());
    }

    /**
     * Testing the difference between simple and attribute property types
     *
     * @throws Exception
     */
    public function testProductPropertyTypes()
    {
        $properties = [
            [
                'type' => Attribute::TYPE,
                'value' => ['attr1', 'attr2']
            ],
            [
                'type' => Simple::TYPE,
                'value' => ['en-us' => ['test1', 'test2']]
            ]
        ];
        $entry = ['products' => [['properties' => $properties]]];
        $getList = new GetList($entry);
        $properties = $getList->getProducts()[0]->getProperties();
        $attrValue = $properties[0]->getValue();
        $simpleValue = $properties[1]->getValue();

        $this->assertInstanceOf(Attribute::class, $properties[0]);
        $this->assertTrue(is_array($attrValue));
        $this->assertEquals(['attr1', 'attr2'], $attrValue);

        $this->assertTrue(is_array($properties));
        $this->assertInstanceOf(Properties::class, $properties[1]);
        $this->assertInstanceOf(Properties\Value::class, $simpleValue);
        $this->assertEquals(['test1', 'test2'], $simpleValue->{'en-us'});
    }
}
