<?php


namespace Shopgate\ConnectSdk\Tests\Integration;

use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook;

class WebhookTest extends ShopgateSdkTest
{
    const WEBHOOK_SERVICE       = 'webook';
    const METHOD_DELETE_WEBHOOK = 'deleteWebhook';
    const WEBHOOK_SIMPLE_PROPS = ['name', 'endpoint', 'active'];

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::WEBHOOK_SERVICE,
            $this->sdk->getWebhooksService(),
            [
                self::METHOD_DELETE_WEBHOOK  => []
            ]
        );
    }

    /**
     * @param Webhook\Get[] $webhooks
     *
     * @return array
     */
    protected function getWebhookCodes($webhooks)
    {
        $webhookCodes = [];
        foreach ($webhooks as $webhook) {
            $webhookCodes[] = $webhook->getCode();
        }

        return $webhookCodes;
    }

    /**
     * @param array $webhooksData
     *
     * @return Webhook\Get[]
     * @throws InvalidDataTypeException
     */
    protected function createSampleWebhooks($webhooksData)
    {
        $webhooks = [];
        foreach ($webhooksData as $webhookData) {
            $webhooks[] = $this->createSampleWebhook($webhookData);
        }

        return $webhooks;
    }

    /**
     * @param array $webhookData
     *
     * @return Webhook\Create
     * @throws InvalidDataTypeException
     */
    protected function createSampleWebhook($webhookData)
    {
        $webhook = new Webhook\Create();

        return $this->addDataToWebhook($webhook, $webhookData);
    }

    /**
     * @param array $webhookData
     *
     * @return Webhook\Update
     * @throws InvalidDataTypeException
     */
    protected function createWebhookUpdate($webhookData)
    {
        $webhook = new Webhook\Update();

        return $this->addDataToWebhook($webhook, $webhookData);
    }

    /**
     * @param Webhook $webhook
     * @param $webhookData
     *
     * @return mixed
     * @throws InvalidDataTypeException
     */
    protected function addDataToWebhook($webhook, $webhookData)
    {
        foreach (self::WEBHOOK_SIMPLE_PROPS as $simpleProp) {
            if (empty($webhookData[$simpleProp])) {
                continue;
            }
            $webhook->set($simpleProp, $webhookData[$simpleProp]);
        }

        if (empty($webhookData['eventsData'])) {
            return $webhook;
        }
        $webhook->set('events', $this->createWebhookEvents($webhookData['eventsData']));

        return $webhook;
    }

    /**
     * @param string[] $eventsData
     *
     * @return Webhook\Dto\Event[]
     * @throws InvalidDataTypeException
     */
    protected function createWebhookEvents($eventsData)
    {
        $events = [];
        foreach ($eventsData as $eventCode) {
            $events[] = new Webhook\Dto\Event(['code' => $eventCode]);
        }

        return $events;
    }

    /**
     * @param string $name
     * @param Webhook\Get[] $webhooks
     *
     * @return Webhook\Get|null
     */
    protected function findWebhookByName($name, $webhooks)
    {
        foreach ($webhooks as $webhook) {
            if ($name === $webhook->getName()) {
                return $webhook;
            }
        }
        return null;
    }
}
