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

class GetTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     */
    public function testCategoryDto()
    {
        $reservation = new Get([
            'productCode' => 'productCode1',
            'locationCode' => 'DERetail1',
            'sku' => 'SKU-1',
            'salesOrderLineItemCode' => 'ddf05955-d47a-4449-a5ee-4c731dfe4952',
            'salesOrderId' => '07838fc-1cba-4529-aeed-ac76a5222822',
            'fulfillmentOrderId' => '0026cc38-f5ad-488f-abf5-e834239e45a1',
            'bin' => '1203',
            'binLocation' => '1203-4',
            'quantity' => 1,
            'code' => '1278ab38-f5ad-488f-abf5-e834239e45a1'
        ]);

        $this->assertEquals('productCode1', $reservation->getProductCode());
        $this->assertEquals('DERetail1', $reservation->getLocationCode());
        $this->assertEquals('SKU-1', $reservation->getSku());
        $this->assertEquals('ddf05955-d47a-4449-a5ee-4c731dfe4952', $reservation->getSalesOrderLineItemCode());
        $this->assertEquals('07838fc-1cba-4529-aeed-ac76a5222822', $reservation->getSalesOrderId());
        $this->assertEquals('0026cc38-f5ad-488f-abf5-e834239e45a1', $reservation->getFulfillmentOrderId());
        $this->assertEquals('1203', $reservation->getBin());
        $this->assertEquals('1203-4', $reservation->getBinLocation());
        $this->assertEquals(1, $reservation->getQuantity());
        $this->assertEquals('1278ab38-f5ad-488f-abf5-e834239e45a1', $reservation->getCode());
    }
}
