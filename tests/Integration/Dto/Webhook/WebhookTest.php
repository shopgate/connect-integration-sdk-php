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
 * @copyright 2019 Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Webhook;

use Shopgate\ConnectSdk\Dto\Webhook\Webhook\Dto\Event;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Tests\Integration\WebhookUtility;

class WebhookTest extends WebhookUtility
{
    /**
     * @param array $webhooksData
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     *
     * @dataProvider provideWebhooksDataCreate
     */
    public function testWebhookCreate($webhooksData)
    {
        // Arrange
        $requestWebhooks = $this->createSampleWebhooks($webhooksData);

        // Act
        $this->sdk->getWebhooksService()->addWebhooks($requestWebhooks);
        $response = $this->sdk->getWebhooksService()->getWebhooks();

        $responseWebhooks = $response->getWebhooks();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::WEBHOOK_SERVICE,
            self::METHOD_DELETE_WEBHOOK,
            $this->getWebhookIds($responseWebhooks)
        );

        // Assert
        $this->assertCount(count($webhooksData), $responseWebhooks);
        foreach ($webhooksData as $webhookData) {
            $foundWebhook = $this->findWebhookByName($webhookData['name'], $responseWebhooks);
            $this->assertNotEmpty($foundWebhook);
            $this->assertEquals($webhookData['endpoint'], $foundWebhook->getEndpoint());
            $this->assertEquals($webhookData['active'], $foundWebhook->getActive());
            $this->assertCount(count($webhookData['eventsData']), $foundWebhook->getEvents());
        }
    }

    public function provideWebhooksDataCreate()
    {
        return [
            'create one' => [
                'webhooksData' => [
                    [
                        'name' => 'test_webhook_one',
                        'endpoint' => 'test/endpoint/one',
                        'active' => true,
                        'eventsData' => [Event::FULFILL_ORDER_STATUS_UPDATED]
                    ]
                ]
            ],
            'create one with multiple events' => [
                'webhooksData' => [
                    [
                        'name' => 'test_webhook_one',
                        'endpoint' => 'test/endpoint/one',
                        'active' => true,
                        'eventsData' => [
                            Event::FULFILL_ORDER_STATUS_UPDATED,
                            Event::INVENTORY_RESERVATION_DELETED,
                            Event::INVENTORY_RESERVATION_SETTLED
                        ]
                    ]
                ]
            ],
            'create more than one' => [
                'webhooksData' => [
                    [
                        'name' => 'test_webhook_one',
                        'endpoint' => 'test/endpoint/one',
                        'active' => true,
                        'eventsData' => [Event::FULFILL_ORDER_STATUS_UPDATED]
                    ],
                    [
                        'name' => 'test_webhook_two',
                        'endpoint' => 'test/endpoint/two',
                        'active' => true,
                        'eventsData' => [
                            Event::FULFILL_ORDER_STATUS_UPDATED,
                            Event::INVENTORY_RESERVATION_DELETED,
                            Event::INVENTORY_RESERVATION_SETTLED
                        ]
                    ],
                    [
                        'name' => 'test_webhook_three',
                        'endpoint' => 'test/endpoint/free',
                        'active' => true,
                        'eventsData' => [
                            Event::ORDER_NOT_PICKED_UP,
                            Event::SALES_ORDER_ADDED,
                            Event::SALES_ORDER_FULFILLMENT_ADDED,
                            Event::SALES_ORDER_STATUS_UPDATED
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * @param array $original
     * @param array $change
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     *
     * @dataProvider provideWebhookDataUpdate
     */
    public function testWebhookUpdate($original, $change)
    {
        // Arrange
        $id = $this->sendCreateWebhooks([$original])[0];
        $updateData = array_merge($original, $change);
        $updateWebhook = $this->createWebhookUpdate($change);

        // Act
        $this->sdk->getWebhooksService()->updateWebhook($id, $updateWebhook);

        // Arrange
        $secondResponse = $this->sdk->getWebhooksService()->getWebhooks();
        $changedWebhook = $this->findWebhookById($id, $secondResponse->getWebhooks());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::WEBHOOK_SERVICE,
            self::METHOD_DELETE_WEBHOOK,
            [$id]
        );

        // Assert
        $this->assertNotEmpty($changedWebhook);

        foreach (self::WEBHOOK_SIMPLE_PROPS as $simpleProp) {
            if (empty($updateData[$simpleProp])) {
                continue;
            }
            $this->assertEquals($updateData[$simpleProp], $changedWebhook->get($simpleProp));
        }

        $this->assertCount(count($updateData['eventsData']), $changedWebhook->getEvents());
    }

    /**
     * @return array
     */
    public function provideWebhookDataUpdate()
    {
        return [
            'change name' => [
                'original' => [
                    'name' => 'test_webhook_one',
                    'endpoint' => 'test/endpoint/one',
                    'active' => true,
                    'eventsData' => [Event::FULFILL_ORDER_STATUS_UPDATED]
                ],
                'change' => [
                    'name' => 'test_webhook_one_new'
                ]
            ],
            'change eventsData' => [
                'original' => [
                    'name' => 'test_webhook_two',
                    'endpoint' => 'test/endpoint/two',
                    'active' => true,
                    'eventsData' => [Event::FULFILL_ORDER_STATUS_UPDATED]
                ],
                'change' => [
                    'eventsData' => [Event::ORDER_NOT_PICKED_UP, Event::SALES_ORDER_FULFILLMENT_ADDED]
                ]
            ],
            'change active' => [
                'original' => [
                    'name' => 'test_webhook_three',
                    'endpoint' => 'test/endpoint/three',
                    'active' => true,
                    'eventsData' => [Event::FULFILL_ORDER_STATUS_UPDATED]
                ],
                'change' => [
                    'active' => false
                ]
            ],
            'change endpoint' => [
                'original' => [
                    'name' => 'test_webhook_four',
                    'endpoint' => 'test/endpoint/four',
                    'active' => true,
                    'eventsData' => [Event::FULFILL_ORDER_STATUS_UPDATED]
                ],
                'change' => [
                    'endpoint' => 'test/endpoint/four_new'
                ]
            ]
        ];
    }

    /**
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function testGetWebhookToken()
    {
        // Act
        $response = $this->sdk->getWebhooksService()->getWebhookToken();

        // Assert
        $this->assertNotEmpty($response->getWebhookToken());
    }

    /**
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function testTriggerWebhookEvent()
    {
        // Arrange
        $code = $this->sendCreateWebhooks([
            [
                'name' => 'test_webhook_one_to_be_triggered',
                'endpoint' => 'test/trigger/one',
                'active' => true,
                'eventsData' => [Event::SALES_ORDER_STATUS_UPDATED]
            ]
        ])[0];
        // Act
        $response = $this->sdk->getWebhooksService()->triggerWebhook($code);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::WEBHOOK_SERVICE,
            self::METHOD_DELETE_WEBHOOK,
            [$code]
        );

        // Assert
        $this->assertNotEmpty($response);
        $this->assertCount(0, $response['error']);
    }

    /**
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function testDeleteWebhook()
    {
        // Arrange
        $id = $this->sendCreateWebhooks([
            [
                'name' => 'test_webhook_one_to_be_deleted',
                'endpoint' => 'test/delete/one',
                'active' => true,
                'eventsData' => [Event::SALES_ORDER_ADDED]
            ]
        ])[0];
        // Act
        $this->sdk->getWebhooksService()->deleteWebhook($id);

        // Arrange
        $response = $this->sdk->getWebhooksService()->getWebhooks();

        // Assert
        $foundWebhook = $this->findWebhookById($id, $response->getWebhooks());
        $this->assertEmpty($foundWebhook);
    }

    /**
     * @param array $webhooksData
     *
     * @return string[]
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    protected function sendCreateWebhooks($webhooksData)
    {
        $createWebhooks = $this->createSampleWebhooks($webhooksData);
        $response = $this->sdk->getWebhooksService()->addWebhooks($createWebhooks);

        return $response['ids'];
    }
}
