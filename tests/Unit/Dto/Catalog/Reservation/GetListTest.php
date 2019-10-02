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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Reservation;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Reservation\Get;
use Shopgate\ConnectSdk\Dto\Catalog\Reservation\GetList;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\Exception;

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
                'limit' => 2,
            ],
            'reservations' => [
                [
                    'productCode' => 'productCode1',
                    'locationCode' => 'DERetail1',
                    'sku' => 'SKU-1',
                    'salesOrderLineItemCode' => 'ddf05955-d47a-4449-a5ee-4c731dfe4952',
                    'salesOrderNumber' => '07838fc-1cba-4529-aeed-ac76a5222822',
                    'quantity' => 1,
                ],
                [
                    'productCode' => 'productCode2',
                    'locationCode' => 'DERetail2',
                    'sku' => 'SKU-2',
                    'salesOrderLineItemCode' => 'ddf05955-d47a-4449-a5ee-4c731dfe4953',
                    'salesOrderNumber' => '07838fc-1cba-4529-aeed-ac76a5222823',
                    'quantity' => 2,
                ]
            ],
        ];

        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(2, $getList->getMeta()->getLimit());

        $reservations = $getList->getReservations();
        $this->assertCount(2, $reservations);
        $this->assertTrue(is_array($reservations));
        $this->assertInstanceOf(Get::class, $reservations[0]);
        $this->assertInstanceOf(Get::class, $reservations[1]);

        $this->assertEquals('productCode1', $reservations[0]->getProductCode());
        $this->assertEquals('DERetail1', $reservations[0]->getLocationCode());
        $this->assertEquals('SKU-1', $reservations[0]->getSku());
        $this->assertEquals('ddf05955-d47a-4449-a5ee-4c731dfe4952', $reservations[0]->getSalesOrderLineItemCode());
        $this->assertEquals('07838fc-1cba-4529-aeed-ac76a5222822', $reservations[0]->getSalesOrderNumber());
        $this->assertEquals(1, $reservations[0]->getQuantity());

        $this->assertEquals('productCode2', $reservations[1]->getProductCode());
        $this->assertEquals('DERetail2', $reservations[1]->getLocationCode());
        $this->assertEquals('SKU-2', $reservations[1]->getSku());
        $this->assertEquals('ddf05955-d47a-4449-a5ee-4c731dfe4953', $reservations[1]->getSalesOrderLineItemCode());
        $this->assertEquals('07838fc-1cba-4529-aeed-ac76a5222823', $reservations[1]->getSalesOrderNumber());
        $this->assertEquals(2, $reservations[1]->getQuantity());
    }
}
