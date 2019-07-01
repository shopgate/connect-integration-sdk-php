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
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Get;

class GetTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     */
    public function testGetDtoClass()
    {
        $entry = [
            'identifiers' => [
                'upc' => 'UPC123'
            ],
            'price'       => [
                'price' => 50.01
            ],
            'categories'  => [
                ['code' => 'la'],
                ['code' => 'la2']
            ]
        ];
        $get   = new Get($entry);
        $ids   = $get->getIdentifiers();
        $this->assertInstanceOf(Dto\Identifiers::class, $ids);
        $this->assertEquals('UPC123', $ids->getUpc());

        $price = $get->getPrice();
        $this->assertInstanceOf(Dto\Price::class, $price);
        $this->assertEquals(50.01, $price->getPrice());

        $categories = $get->getCategories();
        $this->assertCount(2, $categories);
        $this->assertInstanceOf(Dto\Categories::class, $categories[0]);
        $this->assertInstanceOf(Dto\Categories::class, $categories[1]);
        $this->assertEquals('la', $categories[0]->getCode());
        $this->assertEquals('la2', $categories[1]->getCode());
    }
}
