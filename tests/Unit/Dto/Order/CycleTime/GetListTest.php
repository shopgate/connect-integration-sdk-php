<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\CycleTime;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Order\CycleTime\GetList;
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
            'cycleTime' => [
                [
                    'type' => 'timeToAccept',
                    'time' => 156,
                    'difference' => -92
                ]
            ]
        ];

        // Act
        $getList = new GetList($entry);
        $cycleTime = $getList->getCycleTime();
        $get = $cycleTime[0];
        $testEntry = $entry['cycleTime'][0];

        // Assert
        $this->assertEquals($testEntry['type'], $get->getType());
        $this->assertEquals($testEntry['time'], $get->getTime());
        $this->assertEquals($testEntry['difference'], $get->getDifference());
    }
}
