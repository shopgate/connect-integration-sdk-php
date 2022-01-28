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

use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;

class Order
{
    const NAME = 'order';

    /** @var ClientInterface */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $orders
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/SalesOrder/createSalesOrders
     */
    public function addOrders(array $orders)
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'method' => 'post',
                'path' => 'orders',
                'body' => ['orders' => $orders]
            ]
        );
    }

    /**
     * @param array{filters: array|\stdClass, fields: string, limit: int, offset: int} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/SalesOrder/getSalesOrders
     */
    public function getOrders(array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'orders',
                'query' => $query
            ]
        );
    }

    /**
     * @param string $orderNumber
     * @param array{fields: string, getOriginalImageUrls: bool} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/SalesOrder/getSalesOrder
     */
    public function getOrder($orderNumber, array $query = [])
    {
        $response = $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'orders/' . $orderNumber,
                'method' => 'get',
                'query' => $query
            ]
        );

        return isset($response['order']) ? $response['order'] : null;
    }

    /**
     * @param string $orderNumber
     * @param array{filters: array|\stdClass, sort: string} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/SalesOrder/getSalesOrderHistory
     */
    public function getOrderHistory($orderNumber, array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'orders/' . $orderNumber . '/history',
                'query' => $query
            ]
        );
    }

    /**
     * @param string $orderNumber
     * @param array{getOriginalImageUrls: bool} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/FulfillmentOrder/getFulfillmentOrder
     */
    public function getFulfillmentOrder($orderNumber, array $query = [])
    {
        $response = $this->client->request(
            [
                'service' => self::NAME,
                'method' => 'get',
                'path' => 'fulfillmentOrders/' . $orderNumber,
                'query' => $query
            ]
        );

        return isset($response['fulfillmentOrder']) ? $response['fulfillmentOrder'] : null;
    }

    /**
     * @param string $orderNumber
     * @param array{filters: array|\stdClass, sort: string} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/FulfillmentOrder/getFulfillmentOrderHistory
     */
    public function getFulfillmentOrderHistory($orderNumber, array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'fulfillmentOrders/' . $orderNumber . '/history',
                'query' => $query
            ]
        );
    }

    /**
     * @param array{filters: array|\stdClass, sort: string, fields: string, limit: int, offset: int, getOriginalImageUrls: bool} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/FulfillmentOrder/getFulfillmentOrders
     */
    public function getFulfillmentOrders(array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'fulfillmentOrders',
                'query' => $query
            ]
        );
    }

    /**
     * @param array{filters: array|\stdClass} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/Analytics/getFulfillmentOrderStatusCount
     */
    public function getFulfillmentOrderStatusCount(array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'analytics/fulfillmentOrderStatusCount',
                'query' => $query
            ]
        );
    }

    /**
     * @param string $interval
     * @param array{filters: array|\stdClass, timezone: string} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/Analytics/getFulfillmentOrderBreakdownByIntervals
     */
    public function getFulfillmentOrderBreakdown($interval, array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'analytics/fulfillmentOrderBreakdown/intervals/' . $interval,
                'query' => $query
            ]
        );
    }

    /**
     * @param string $interval
     * @param array{filters: array|\stdClass, timezone: string} $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/Analytics/getIntervalCycleTimes
     */
    public function getCycleTimes($interval, array $query = [])
    {
        $filters = null;
        if (!empty($query['filters'])) {
            $filters = $query['filters'];
            unset($query['filters']);
        }

        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'analytics/cycleTimes/intervals/' . $interval,
                'query' => $query,
                'filters' => $filters
            ]
        );
    }
}
