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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Webhook;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook\GetList;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook\Get;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook\Dto\Event;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

class CreateGetList extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     */
    public function testBasicProperties()
    {
        $entry = [
            'webhooks' => [
                [
                    'name' => 'test_webhook',
                    'endpoint' => 'test/webhook/endpoint',
                    'active' => true,
                    'events' => [
                        ['code' => 'fulfillmentOrderStatusUpdated']
                    ],
                    'code' => '123-abc-987-zyx',
                ]
            ]
        ];

        $getList = new GetList($entry);
        $get = $getList->getWebhooks()[0];
        $entryWebhook = $entry['webhooks'][0];
        $this->assertInstanceOf(Get::class, $get);
        $this->assertEquals($entryWebhook['name'], $get->getName());
        $this->assertEquals($entryWebhook['endpoint'], $get->getEndpoint());
        $this->assertEquals($entryWebhook['active'], $get->getActive());
        $this->assertEquals($entryWebhook['code'], $get->getCode());
        $event = $get->getEvents()[0];
        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($entryWebhook['events'][0]['code'], $event->getCode());
    }
}
