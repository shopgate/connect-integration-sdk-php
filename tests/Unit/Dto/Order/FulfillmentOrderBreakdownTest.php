<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrderBreakdown;
use Shopgate\ConnectSdk\Exception\Exception;

class FulfillmentOrderBreakdownTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBasicProperties()
    {
        // Arrange
        $entry = [
            'numberOfOrders' => 0,
            'averageNumberOfItems' => 0,
            'averageOrderValue' => 0
        ];

        // Act
        $orderBreakdown = new FulfillmentOrderBreakdown($entry);

        // Assert
        $this->assertEquals($entry['numberOfOrders'], $orderBreakdown->getNumberOfOrders());
        $this->assertEquals($entry['averageNumberOfItems'], $orderBreakdown->getAverageNumberOfItems());
        $this->assertEquals($entry['averageOrderValue'], $orderBreakdown->getAverageOrderValue());
    }
}
