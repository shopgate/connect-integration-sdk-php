<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\FulfillmentOrderStatusCount;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrderStatusCount\Get;
use Shopgate\ConnectSdk\Exception\Exception;

class GetTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBasicProperties()
    {
        // Arrange
        $entry = [
            'status' => 'new',
            'count' => 0
        ];

        // Act
        $get = new Get($entry);

        // Assert
        $this->assertEquals($entry['status'], $get->getStatus());
        $this->assertEquals($entry['count'], $get->getCount());
    }
}
