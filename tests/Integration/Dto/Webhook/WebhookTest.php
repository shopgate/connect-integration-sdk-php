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
        $this->markTestSkipped(
            'Something unexpected is happening with event changes'
        );
        // Arrange
        $createWebhook = $this->createSampleWebhook($original);
        $updateWebhook = $this->createWebhookUpdate(array_merge($original, $change));
        $this->sdk->getWebhooksService()->addWebhooks([$createWebhook]);
        $response = $this->sdk->getWebhooksService()->getWebhooks();
        $orignalWebhook = $this->findWebhookByName($original['name'], $response->getWebhooks());

        // Act
        $this->sdk->getWebhooksService()->updateWebhook($orignalWebhook->getCode(), $updateWebhook);

        // Arrange
        $secondResponse = $this->sdk->getWebhooksService()->getWebhooks();
        $changedWebhook = $this->findWebhookByName($updateWebhook->get('name'), $secondResponse->getWebhooks());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::WEBHOOK_SERVICE,
            self::METHOD_DELETE_WEBHOOK,
            array_unique([$orignalWebhook->getCode(), $changedWebhook->getCode()])
        );

        // Assert
        $this->assertNotEmpty($changedWebhook);
        $mergedOriginalChange = array_merge($original, $change);
        foreach (self::WEBHOOK_SIMPLE_PROPS as $simpleProp) {
            if (empty($mergedOriginalChange[$simpleProp])) {
                continue;
            }
            $this->assertEquals($mergedOriginalChange[$simpleProp], $changedWebhook->get($simpleProp));
        }
        $totalEvents = 0;
        if (!empty($original['eventsData'])) {
            $totalEvents += count($original['eventsData']);
        }
        if (!empty($change['eventsData'])) {
            $totalEvents += count($change['eventsData']);
        }
        $this->assertCount($totalEvents, $changedWebhook->getEvents());

    }

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
            ]
        ];
    }
}
