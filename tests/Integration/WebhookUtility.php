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

namespace Shopgate\ConnectSdk\Tests\Integration;

use Shopgate\ConnectSdk\Dto\Webhook\Webhook;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

abstract class WebhookUtility extends ShopgateSdkUtility
{
    const WEBHOOK_SIMPLE_PROPS = ['name', 'endpoint', 'active'];

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
     * @param         $webhookData
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
     * @param string        $name
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

    /**
     * @param string        $code
     * @param Webhook\Get[] $webhooks
     *
     * @return Webhook\Get|null
     */
    protected function findWebhookByCode($code, $webhooks)
    {
        foreach ($webhooks as $webhook) {
            if ($code === $webhook->getCode()) {
                return $webhook;
            }
        }
        return null;
    }
}
