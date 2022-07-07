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

    #####################################################################################################
    # Sales Orders
    #####################################################################################################

    /**
     * @param array[] $orders
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDM-create-sales-orders
     */
    public function addOrders(array $orders, array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'method' => 'post',
                'path' => 'orders',
                'body' => ['orders' => $orders],
                'query' => $query
            ]
        );
    }

    /**
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDI-get-sales-orders
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
     * @param array $query
     *
     * @return array|null
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDQ-get-sales-order
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
     * @param array $order
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDU-update-sales-order
     */
    public function updateSalesOrder($orderNumber, array $order, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'orders/' . $orderNumber,
            'body' => $order,
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDg-get-history-of-sales-order
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
     * @param array $checkoutData
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDk-create-sales-order-checkout
     */
    public function checkoutOrder($orderNumber, array $checkoutData, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'orders/' . $orderNumber . '/checkout',
            'body' => $checkoutData,
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array[] $fulfillmentGroups
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTA-create-sales-order-fulfillment-groups
     */
    public function addSalesOrderFulfillmentGroups($orderNumber, array $fulfillmentGroups, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'orders/' . $orderNumber . '/fulfillmentGroups',
            'body' => ['fulfillmentGroups' => $fulfillmentGroups],
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Fulfillment Orders
    #####################################################################################################

    /**
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTE-get-fulfillment-orders
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
     * @param array $fulfillmentOrders
     * @param array[] $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTI-create-fulfillment-orders
     */
    public function addFulfillmentOrders(array $fulfillmentOrders, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'fulfillmentOrders',
            'body' => ['fulfillmentOrders' => $fulfillmentOrders],
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array $fulfillmentOrder
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTU-update-fulfillment-order
     */
    public function updateFulfillmentOrder($orderNumber, array $fulfillmentOrder, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'fulfillmentOrders/' . $orderNumber,
            'body' => $fulfillmentOrder,
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array $simpleFulfillmentOrders
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTM-create-simple-fulfillment-orders-for-order
     */
    public function createSimpleFulfillmentOrdersFromOrder($orderNumber, array $simpleFulfillmentOrders, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'orders/' . $orderNumber . '/simpleFulfillmentOrders',
            'body' => ['simpleFulfillmentOrders' => $simpleFulfillmentOrders],
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array $query
     *
     * @return array|null
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTQ-get-fulfillment-order
     */
    public function getFulfillmentOrder($orderNumber, array $query = [])
    {
        $response = $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'fulfillmentOrders/' . $orderNumber,
                'query' => $query
            ]
        );

        return isset($response['fulfillmentOrder']) ? $response['fulfillmentOrder'] : null;
    }

    /**
     * @param string $orderNumber
     * @param array[] $fulfillments
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTc-create-fulfillments-for-fulfillment-order
     */
    public function addFulfillmentOrderFulfillments($orderNumber, array $fulfillments, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'fulfillmentOrders/' . $orderNumber . '/fulfillments',
            'body' => ['fulfillments' => $fulfillments],
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param string $fulfillmentId
     * @param array $fulfillment
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTg-update-fulfillments-of-fulfillment-order
     */
    public function updateFulfillmentOrderFulfillment($orderNumber, $fulfillmentId, array $fulfillment, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'fulfillmentOrders/' . $orderNumber . '/fulfillments/' . $fulfillmentId,
            'body' => $fulfillment,
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMTk-get-history-of-fulfillment-order
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
     * @param string $orderNumber
     * @param string $lineItemId
     * @param string $status
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjA-update-line-item-status-of-fulfillment-order
     */
    public function updateFulfillmentOrderLineItemStatus($orderNumber, $lineItemId, $status, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'put',
            'path' => 'fulfillmentOrders/' . $orderNumber . '/lineItems/' . $lineItemId . '/status',
            'body' => ['status' => $status],
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Return Orders
    #####################################################################################################

    /**
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     */
    public function getReturnOrders(array $query = [])
    {
        return $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'returnOrders',
                'query' => $query
            ]
        );
    }

    /**
     * @param array $returnOrders
     * @param array[] $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function addReturnOrders(array $returnOrders, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'returnOrders',
            'body' => ['returnOrders' => $returnOrders],
            'query' => $query
        ]);
    }

    /**
     * @param string $orderNumber
     * @param array $query
     *
     * @return array|null
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     */
    public function getReturnOrder($orderNumber, array $query = [])
    {
        $response = $this->client->request(
            [
                'service' => self::NAME,
                'path' => 'returnOrders/' . $orderNumber,
                'query' => $query
            ]
        );

        return isset($response['returnOrder']) ? $response['returnOrder'] : null;
    }

    /**
     * @param string $orderNumber
     * @param array $returnOrder
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function updateReturnOrder($orderNumber, array $returnOrder, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'returnOrders/' . $orderNumber,
            'body' => $returnOrder,
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Picking Batches
    #####################################################################################################

    /**
     * @param string $locationCode
     * @param array[] $pickingBatches
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjM-create-picking-batches
     */
    public function addPickingBatches($locationCode, array $pickingBatches, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'pickingBatches',
            'body' => ['locationCode' => $locationCode, 'pickingBatches' => $pickingBatches],
            'query' => $query
        ]);
    }

    /**
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjQ-get-picking-batches
     */
    public function getPickingBatches(array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'pickingBatches',
            'query' => $query
        ]);
    }

    /**
     * @param string $locationCode
     * @param int $fulfillmentOrderCount
     *
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjU-generate-picking-batch
     */
    public function generatePickingBatches($locationCode, $fulfillmentOrderCount, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'pickingBatches/generate',
            'body' => ['locationCode' => $locationCode, 'fulfillmentOrderCount' => $fulfillmentOrderCount],
            'query' => $query
        ]);
    }

    /**
     * @param string $pickingBatchId
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjY-get-picking-batch
     */
    public function getPickingBatch($pickingBatchId, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'pickingBatches/' . $pickingBatchId,
            'query' => $query
        ]);
    }

    /**
     * @param string $pickingBatchId
     * @param array $pickingBatch
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjc-update-picking-batch
     */
    public function updatePickingBatch($pickingBatchId, array $pickingBatch, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'pickingBatches/' . $pickingBatchId,
            'body' => $pickingBatch,
            'query' => $query
        ]);
    }

    /**
     * @param string $pickingBatchId
     * @param array[] $lineItems
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjg-update-picking-batch-line-items
     */
    public function updatePickingBatchLineItems($pickingBatchId, array $lineItems, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'pickingBatches/' .$pickingBatchId . '/lineItems',
            'body' => $lineItems,
            'query' => $query
        ]);
    }

    /**
     * @param string $pickingBatchId
     * @param string[] $fulfillmentOrderNumbers
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMjk-delete-fulfillment-order-from-picking-batch
     */
    public function deleteFulfillmentOrdersFromPickingBatch($pickingBatchId, array $fulfillmentOrderNumbers, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'pickingBatches/' . $pickingBatchId . '/fulfillmentOrders',
            'body' => ['fulfillmentOrderNumbers' => $fulfillmentOrderNumbers],
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Analytics
    #####################################################################################################

    /**
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgwOTg-get-fulfillment-order-status-count
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
     * @param array $query
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgwOTk-get-fulfillment-order-breakdown-by-intervals
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
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDQ3MjgxMDA-get-sales-orders-reportings
     */
    public function getSalesOrderReporting(array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'analytics/salesOrderReporting',
            'query' => $query
        ]);
    }

    /**
     * @param string $interval
     * @param array $query
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
