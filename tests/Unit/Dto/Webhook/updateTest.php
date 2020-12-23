<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Webhook;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook\Update;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook\Dto\Event;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

class updateTest extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     */
    public function testBasicProperties()
    {
        $entry = [
            'name' => 'test_webhook',
            'endpoint' => 'test/webhook/endpoint',
            'active' => true,
            'events' => [
                ['code' => 'fulfillmentOrderStatusUpdated']
            ]
        ];

        $update = new Update($entry);
        $this->assertInstanceOf(Update::class, $update);
        $this->assertEquals($entry['name'], $update->get('name'));
        $this->assertEquals($entry['endpoint'], $update->get('endpoint'));
        $this->assertEquals($entry['active'], $update->get('active'));
        $event = $update->get('events')[0];
        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($entry['events'][0]['code'], $event->getCode());
    }
}
