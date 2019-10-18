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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\FulfillmentOrderStatusCount;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrderStatusCount\GetList;
use Shopgate\ConnectSdk\Exception\Exception;

class GetListTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBasicProperties()
    {
        // Arrange
        $entry = [
            'orderStatusCount' => [
                [
                    'status' => 'new',
                    'count' => 0
                ]
            ]
        ];

        // Act
        $getList = new GetList($entry);
        $orderStatusCount = $getList->getOrderStatusCount();
        $get = $orderStatusCount[0];
        $testEntry = $entry['orderStatusCount'][0];

        // Assert
        $this->assertEquals($testEntry['status'], $get->getStatus());
        $this->assertEquals($testEntry['count'], $get->getCount());
    }
}
