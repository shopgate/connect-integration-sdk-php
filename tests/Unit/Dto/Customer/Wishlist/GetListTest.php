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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Customer\Wishlist;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Customer\Wishlist\Get;
use Shopgate\ConnectSdk\Dto\Customer\Wishlist\GetList;
use Shopgate\ConnectSdk\Dto\Meta;

class GetListTest extends TestCase
{
    public function testBasicDtoList()
    {
        $codeOne        = 'someCodeOne';
        $nameOne        = 'some name one';
        $productCodeOne = 'productCodeOne';

        $codeTwo        = 'someCodeTwo';
        $nameTwo        = 'some name two';
        $productCodeTwo = 'productCodeTwo';

        $entry = [
            'meta'      => [],
            'wishlists' => [
                [
                    'code'  => $codeOne,
                    'name'  => $nameOne,
                    'items' => [
                        [
                            'productCode' => $productCodeOne
                        ]
                    ]
                ],
                [
                    'code'  => $codeTwo,
                    'name'  => $nameTwo,
                    'items' => [
                        [
                            'productCode' => $productCodeTwo
                        ]
                    ]
                ]
            ]
        ];

        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());

        $wishlists = $getList->getWishlists();
        $this->assertCount(2, $wishlists);
        $this->assertInstanceOf(Get::class, $wishlists);
        $this->assertInstanceOf(Get::class, $wishlists[0]);
        $this->assertInstanceOf(Get::class, $wishlists[1]);
        $this->assertEquals($codeOne, $wishlists[0]->getCode());
        $this->assertEquals($nameOne, $wishlists[0]->getName());
        $this->assertEquals($productCodeOne, $wishlists[0]->getItems()[0]->getProductCode());
        $this->assertEquals($codeTwo, $wishlists[1]->getCode());
        $this->assertEquals($nameTwo, $wishlists[1]->getName());
        $this->assertEquals($productCodeTwo, $wishlists[1]->getItems()[0]->getProductCode());
    }
}
