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
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\Helper\Json;

class Webhook
{
    const NAME = 'webhook';

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
     * @param array $webhooks
     * @param array $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws TokenPersistenceException
     */
    public function addWebhooks(array $webhooks, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'webhooks',
            'json' => true,
            'body' => ['webhooks' => $webhooks],
            'query' => $query
        ]);
    }

    /**
     * @param string $id
     * @param array $webhook
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function updateWebhook($id, array $webhook)
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'webhooks/' . $id,
            'body' => $webhook
        ]);
    }

    /**
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function getWebhooks(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'webhooks',
            'query' => $query
        ]);
    }

    /**
     * @param string $id
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function deleteWebhook($id)
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'webhooks/' . $id
        ]);
    }

    /**
     * @param string $code
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function triggerWebhook($code)
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'webhooks/' . $code . '/test'
        ]);
    }

    /**
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function getWebhookToken()
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'webhookToken'
        ]);
    }
}
