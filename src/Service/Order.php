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
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder;
use Shopgate\ConnectSdk\Dto\Order\Order as OrderDto;
use Shopgate\ConnectSdk\Dto\Order\SimpleFulfillmentOrder;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrderStatusCount;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrderBreakdown;
use Shopgate\ConnectSdk\Dto\Order\CycleTime;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

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
        $this->client = $client;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @param OrderDto\Create[] $orders
     * @param array             $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addOrders(array $orders, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // general
                'method' => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json' => ['orders' => $orders],
                'query' => $query,
                // direct
                'service' => self::SERVICE_ORDER,
                'path' => 'orders'
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
     * @throws InvalidDataTypeException
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
                'method' => 'get',
                'path' => 'orders',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $orders = [];
        foreach ($response['orders'] as $order) {
            $orders[] = new OrderDto\Get($order);
        }
        $response['meta'] = new Meta($response['meta']);
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
     * @throws InvalidDataTypeException
     */
    public function getOrder($orderNumber, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method' => 'get',
                'path' => 'orders/' . $orderNumber,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new OrderDto\Get($response['order']);
    }

    /**
     * @param string $orderNumber
     * @param array  $query
     *
     * @return FulfillmentOrder\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getFulfillmentOrder($orderNumber, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method' => 'get',
                'path' => 'fulfillmentOrders/' . $orderNumber,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new FulfillmentOrder\Get($response['fulfillmentOrder']);
    }

    /**
     * @param array $query
     *
     * @return FulfillmentOrder\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getFulfillmentOrders(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method' => 'get',
                'path' => 'fulfillmentOrders',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $orders = [];
        foreach ($response['fulfillmentOrders'] as $order) {
            $orders[] = new SimpleFulfillmentOrder($order);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['fulfillmentOrders'] = $orders;

        return new FulfillmentOrder\GetList($response);
    }

    /**
     * @param array $query
     *
     * @return FulfillmentOrderStatusCount[]
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getFulfillmentOrderStatusCount(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method' => 'get',
                'path' => 'analytics/fulfillmentOrderStatusCount',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $orderStatusCount = [];
        foreach ($response['orderStatusCount'] as $statusCount) {
            $orderStatusCount[] = new FulfillmentOrderStatusCount($statusCount);
        }

        return $orderStatusCount;
    }

    /**
     * @param string $interval
     * @param array $query
     *
     * @return FulfillmentOrderBreakdown
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getFulfillmentOrderBreakdown($interval, array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method' => 'get',
                'path' => 'analytics/fulfillmentOrderBreakdown/intervals/' . $interval,
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new FulfillmentOrderBreakdown($response['orderBreakdown']);
    }

    /**
     * @param string $interval
     * @param array $query
     *
     * @return CycleTime[]
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getCycleTimes($interval, array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_ORDER,
                'method' => 'get',
                'path' => 'analytics/cycleTimes/intervals/' . $interval,
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $cycleTimes = [];
        foreach ($response['cycleTime'] as $cycleTime) {
            $cycleTimes[] = new CycleTime($cycleTime);
        }

        return $cycleTimes;
    }
}
