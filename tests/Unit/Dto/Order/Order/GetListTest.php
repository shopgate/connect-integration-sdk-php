<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\Order;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\Order\GetList;
use Shopgate\ConnectSdk\Dto\Order\Order\Get;
use Shopgate\ConnectSdk\Dto\Meta;

class GetListTest extends TestCase
{
    public function testCategoryDto()
    {
        $entry = [
            'meta' => [
                'limit' => 1
            ],
            'orders' => [
                [
                    'orderNumber' => 'test-order-one',
                    'externalCode' => 'test-external-code-one'
                ],
                [
                    'orderNumber' => 'test-order-two',
                    'externalCode' => 'test-external-code-two'
                ]
            ]
        ];
        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(1, $getList->getMeta()->getLimit());
        $orders = $getList->getOrders();
        $this->assertCount(2, $orders);
        $this->assertInstanceOf(Get::class, $orders[0]);
        $this->assertInstanceOf(Get::class, $orders[1]);
        $this->assertEquals($entry['orders'][0]['orderNumber'], $orders[0]->getOrderNumber());
        $this->assertEquals($entry['orders'][0]['externalCode'], $orders[0]->getExternalCode());
        $this->assertEquals($entry['orders'][1]['orderNumber'], $orders[1]->getOrderNumber());
        $this->assertEquals($entry['orders'][1]['externalCode'], $orders[1]->getExternalCode());
    }
}
