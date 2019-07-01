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
use Shopgate\ConnectSdk\Dto\Catalog\Product\Get;
use Shopgate\ConnectSdk\Dto\Catalog\Product\GetList;
use Shopgate\ConnectSdk\Dto\Meta;

class GetListTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     */
    public function testGetListDto()
    {
        $entry   = [
            'meta'     => [
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
}
