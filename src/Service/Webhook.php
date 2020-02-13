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

namespace Shopgate\ConnectSdk\Service;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Webhook\Webhook as WebhookDto;
use Shopgate\ConnectSdk\Dto\Webhook\WebhookToken;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Helper\Json;

class Webhook
{
    const SERVICE_WEBHOOK = 'webhook';
    const WEBHOOK_PATH = 'webhooks';

    /** @var ClientInterface */
    private $client;

    /** @var Json */
    private $jsonHelper;

    /**
     * @param ClientInterface $client
     * @param Json            $jsonHelper
     */
    public function __construct(ClientInterface $client, Json $jsonHelper)
    {
        $this->client = $client;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @param WebhookDto\Create[] $webhooks
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addWebhooks(array $webhooks)
    {
        $response = $this->client->doRequest(
            [
                // general
                'method' => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json' => ['webhooks' => $webhooks],
                // direct
                'service' => self::SERVICE_WEBHOOK,
                'path' => self::WEBHOOK_PATH
            ]
        );

        return $this->jsonHelper->decode($response->getBody(), true);
    }

    /**
     * @param string            $id
     * @param WebhookDto\Update $webhook
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function updateWebhook($id, WebhookDto\Update $webhook)
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json' => $webhook,
                // direct
                'method' => 'post',
                'service' => self::SERVICE_WEBHOOK,
                'path' => self::WEBHOOK_PATH . '/' . $id
            ]
        );
    }

    /**
     * @param array $query
     *
     * @return WebhookDto\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getWebhooks(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_WEBHOOK,
                'method' => 'get',
                'path' => self::WEBHOOK_PATH,
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);
        $webhooks = [];
        foreach ($response['webhooks'] as $webhook) {
            $webhooks[] = new WebhookDto\Get($webhook);
        }
        $response['webhooks'] = $webhooks;

        return new WebhookDto\GetList($response);
    }

    /**
     * @param string $id
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteWebhook($id)
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                // direct
                'method' => 'delete',
                'service' => self::SERVICE_WEBHOOK,
                'path' => self::WEBHOOK_PATH . '/' . $id,
            ]
        );
    }

    /**
     * @param string $code
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function triggerWebhook($code)
    {
        $response = $this->client->doRequest(
            [
                // general
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                // direct
                'method' => 'post',
                'json' => [],
                'service' => self::SERVICE_WEBHOOK,
                'path' => self::WEBHOOK_PATH . '/' . $code . '/test',
            ]
        );

        return $this->jsonHelper->decode($response->getBody(), true);
    }

    /**
     * @return WebhookToken\Get
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getWebhookToken()
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_WEBHOOK,
                'method' => 'get',
                'path' => 'webhookToken',
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new WebhookToken\Get($response);
    }
}
