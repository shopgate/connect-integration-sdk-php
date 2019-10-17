<?php


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
