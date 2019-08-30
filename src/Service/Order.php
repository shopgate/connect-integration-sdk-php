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
use Shopgate\ConnectSdk\Dto\Order\Order as OrderDto;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Helper\Json;

class Order
{
    const SERVICE_ORDER = 'order';

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
        $this->client     = $client;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @param OrderDto\Create[] $orders
     * @param array                $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addOrders(array $orders, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json'        => ['orders' => $orders],
                'query'       => $query,
                // direct
                'service'     => self::SERVICE_ORDER,
                'path'        => 'orders'
            ]
        );

        return $this->jsonHelper->decode($response->getBody(), true);
    }

    /**
     * @param array $query
     *
     * @return OrderDto\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getOrders(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method'  => 'get',
                'path'    => 'orders',
                'query'   => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $orders = [];
        foreach ($response['orders'] as $order) {
            $orders[] = new OrderDto\Get($order);
        }
        $response['meta']       = new Meta($response['meta']);
        $response['orders'] = $orders;

        return new OrderDto\GetList($response);
    }

    /**
     * @param string $orderNumber
     * @param array  $query
     *
     * @return OrderDto\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getOrder($orderNumber, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method'  => 'get',
                'path'    => 'orders/' . $orderNumber,
                'query'   => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new OrderDto\Get($response['order']);
    }
}
