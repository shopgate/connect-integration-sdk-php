<?php


namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Webhook;

use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Tests\Integration\WebhookTest as WebhookBaseTest;

class WebhookTest extends WebhookBaseTest
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
        $requestWebooks = $this->createSampleWebhooks($webhooksData);

        // Act
        $this->sdk->getWebhooksService()->addWebhooks($requestWebooks);
        $response = $this->sdk->getWebhooksService()->getWebhooks();

        $responseWebhooks = $response->getWebhooks();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::WEBHOOK_SERVICE,
            self::METHOD_DELETE_WEBHOOK,
            $this->getWebhookCodes($responseWebhooks)
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
                        'eventsData' => ['fulfillmentOrderStatusUpdated']
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
                            'fulfillmentOrderStatusUpdated',
                            'inventoryReservationDeleted',
                            'inventoryReservationSettled'
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
                        'eventsData' => ['fulfillmentOrderStatusUpdated']
                    ],
                    [
                        'name' => 'test_webhook_two',
                        'endpoint' => 'test/endpoint/two',
                        'active' => true,
                        'eventsData' => [
                            'fulfillmentOrderStatusUpdated',
                            'inventoryReservationDeleted',
                            'inventoryReservationSettled'
                        ]
                    ],
                    [
                        'name' => 'test_webhook_three',
                        'endpoint' => 'test/endpoint/free',
                        'active' => true,
                        'eventsData' => [
                            'orderNotPickedUp',
                            'salesOrderAdded',
                            'salesOrderFulfillmentAdded',
                            'salesOrderStatusUpdated'
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
        $code = $this->sendCreateWebhooks([$original])[0];
        $updateData = array_merge($original, $change);
        $updateWebhook = $this->createWebhookUpdate($change);

        // Act
        $this->sdk->getWebhooksService()->updateWebhook($code, $updateWebhook);

        // Arrange
        $secondResponse = $this->sdk->getWebhooksService()->getWebhooks();
        $changedWebhook = $this->findWebhookByCode($code, $secondResponse->getWebhooks());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::WEBHOOK_SERVICE,
            self::METHOD_DELETE_WEBHOOK,
            [$code]
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
                    'eventsData' => ['fulfillmentOrderStatusUpdated']
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
                    'eventsData' => ['fulfillmentOrderStatusUpdated']
                ],
                'change' => [
                    'eventsData' => ['orderNotPickedUp', 'salesOrderFulfillmentAdded']
                ]
            ],
            'change active' => [
                'original' => [
                    'name' => 'test_webhook_three',
                    'endpoint' => 'test/endpoint/three',
                    'active' => true,
                    'eventsData' => ['fulfillmentOrderStatusUpdated']
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
                    'eventsData' => ['fulfillmentOrderStatusUpdated']
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
                'eventsData' => ['salesOrderStatusUpdated']
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
        $code = $this->sendCreateWebhooks([
            [
                'name' => 'test_webhook_one_to_be_deleted',
                'endpoint' => 'test/delete/one',
                'active' => true,
                'eventsData' => ['salesOrderAdded']
            ]
        ])[0];
        // Act
        $this->sdk->getWebhooksService()->deleteWebhook($code);

        // Arrange
        $response = $this->sdk->getWebhooksService()->getWebhooks();

        // Assert
        $foundWebook = $this->findWebhookByCode($code, $response->getWebhooks());
        $this->assertEmpty($foundWebook);
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

        return $response['codes'];
    }
}
