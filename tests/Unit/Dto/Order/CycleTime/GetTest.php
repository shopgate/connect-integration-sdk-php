<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\CycleTime;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\CycleTime\Get;
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
            'type' => 'timeToAccept',
            'time' => 156,
            'difference' => -92
        ];

        // Act
        $get = new Get($entry);

        // Assert
        $this->assertEquals($entry['type'], $get->getType());
        $this->assertEquals($entry['time'], $get->getTime());
        $this->assertEquals($entry['difference'], $get->getDifference());
    }
}
