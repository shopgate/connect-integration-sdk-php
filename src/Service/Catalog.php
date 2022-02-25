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
use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Dto\Catalog\Catalog as CatalogDto;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Inventory;
use Shopgate\ConnectSdk\Dto\Catalog\ParentCatalog;
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Catalog\ProductDescriptions;
use Shopgate\ConnectSdk\Dto\Catalog\Reservation;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Helper\Value;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\ShopgateSdk;

class Catalog
{
    const NAME = 'catalog';

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
     * @param array $categories
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
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Categories/createCategories
     */
    public function addCategories(array $categories, array $query = [])
    {
        $requestType = isset($query['requestType']) ? $query['requestType'] : ShopgateSdk::REQUEST_TYPE_DIRECT;
        if ($requestType === ShopgateSdk::REQUEST_TYPE_EVENT) {
            if (!empty($query['catalogCode'])) {
                $categories = Value::addValue($categories, $query['catalogCode'], 'catalogCode');
            }

            return $this->client->publish('entityCreated', 'category', $categories);
        }

        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'categories',
            'json' => true,
            'body' => ['categories' => $categories],
            'query' => $query
        ]);
    }

    /**
     * @param string $code
     * @param array $category
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Categories/updateCategory
     */
    public function updateCategory($code, array $category, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'categories/' . $code,
            'body' => $category,
            'query' => $query
        ]);
    }

    /**
     * @param string $code
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Categories/deleteCategory
     */
    public function deleteCategory($code, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'categories/' . $code,
            'query' => $query
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Categories/getCategories
     */
    public function getCategories(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'categories',
            'query' => $query
        ]);
    }

    /**
     * @param array $products
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Products/createProducts
     */
    public function addProducts(array $products, array $query = [])
    {
        $requestType = isset($query['requestType']) ? $query['requestType'] : ShopgateSdk::REQUEST_TYPE_DIRECT;
        if ($requestType === ShopgateSdk::REQUEST_TYPE_EVENT) {
            if (!empty($query['catalogCode'])) {
                $products = Value::addValue($products, $query['catalogCode'], 'catalogCode');
            }

            return $this->client->publish('entityCreated', 'product', $products);
        }

        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'products',
            'json' => true,
            'body' => ['products' => $products],
            'query' => $query
        ]);
    }

    /**
     * @param string $code
     * @param array $product
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Products/updateProduct
     */
    public function updateProduct($code, array $product, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'products/' . $code,
            'body' => $product,
            'query' => $query
        ]);
    }

    /**
     * @param string $code
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Products/deleteProduct
     */
    public function deleteProduct($code, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'products/' . $code,
            'query' => $query
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Products/getProducts
     */
    public function getProducts(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'products',
            'query' => $query
        ]);
    }

    /**
     * @param string $code - product code
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Products/getProduct
     */
    public function getProduct($code, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'products/' . $code,
            'query' => $query
        ]);

        return isset($response['product']) ? $response['product'] : null;
    }

    /**
     * @param string $code - product code
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Products/getProductDescriptions
     */
    public function getProductDescriptions($code, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'products/' . $code . '/descriptions',
            'query' => $query
        ]);
    }

    /**
     * @param array $attributes
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/createAttributes
     */
    public function addAttributes(array $attributes, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'attributes',
            'json' => true,
            'body' => ['attributes' => $attributes],
            'query' => $query
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/getAttributes
     */
    public function getAttributes(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'attributes',
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/getAttribute
     */
    public function getAttribute($attributeCode, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'attributes/' . $attributeCode,
            'query' => $query
        ]);

        return isset($response['attribute']) ? $response['attribute'] : null;
    }

    /**
     * @param string $attributeCode
     * @param array $attribute
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/updateAttribute
     */
    public function updateAttribute($attributeCode, array $attribute, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'attributes/' . $attributeCode,
            'body' => $attribute,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/deleteAttribute
     */
    public function deleteAttribute($attributeCode, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'attributes/' . $attributeCode,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array $attributeValues
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/setAttributeValues
     */
    public function addAttributeValues($attributeCode, array $attributeValues, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'attributes/' . $attributeCode . '/values',
            'json' => true,
            'body' => ['values' => $attributeValues],
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param string $attributeValueCode
     * @param array $attributeValue
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/updateAttributeValue
     */
    public function updateAttributeValue($attributeCode, $attributeValueCode, array $attributeValue, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
            'body' => $attributeValue,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param string $attributeValueCode
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Attributes/deleteAttributeValue
     */
    public function deleteAttributeValue($attributeCode, $attributeValueCode, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
            'query' => $query
        ]);
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
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventories/getInventories
     */
    public function getInventories(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'inventories',
            'query' => $query
        ]);
    }

    /**
     * @param array $inventories
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventories/setInventories
     */
    public function addInventories(array $inventories, array $query = [])
    {
        // The event receiver does not yet support entities of type inventory
        // $requestType = isset($query['requestType']) ? $query['requestType'] : ShopgateSdk::REQUEST_TYPE_DIRECT;
        // if ($requestType === ShopgateSdk::REQUEST_TYPE_EVENT) {
        //      return $this->client->publish('entityCreated', 'inventory', $inventories);
        // }

        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'inventories',
            'json' => true,
            'body' => ['inventories' => $inventories],
            'query' => $query
        ]);
    }

    /**
     * @param array $inventories
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventories/deleteInventories
     */
    public function deleteInventories(array $inventories, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'inventories',
            'body' => ['inventories' => $inventories],
            'query' => $query
        ]);
    }

    /**
     * @param array $inventories
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventories/incrementDecrementInventory
     */
    public function updateInventories($inventories, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'patch',
            'path' => 'inventories',
            'body' => ['inventories' => $inventories],
            'query' => $query
        ]);
    }

    /**
     * @param array $reservations
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
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventory/reserveInventory
     */
    public function addReservations(array $reservations, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'reservations',
            'json' => true,
            'body' => ['reservations' => $reservations],
            'query' => $query
        ]);
    }

    /**
     * @param array $codes
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventory/deleteInventoryReservations
     */
    public function deleteReservations(array $codes, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'reservations',
            'body' => ['codes' => $codes],
            'query' => $query
        ]);
    }

    /**
     * @param string $reservationCode
     * @param array $reservation
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventory/updateReservation
     */
    public function updateReservation($reservationCode, $reservation, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'reservations/' . $reservationCode,
            'body' => $reservation,
            'query' => $query
        ]);
    }

    /**
     * @param string $reservationCode
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventory/getReservation
     */
    public function getReservation($reservationCode, array $query = [])
    {
        $response =  $this->client->request([
            'service' => self::NAME,
            'path' => 'reservations/' . $reservationCode,
            'query' => $query
        ]);

        return isset($response['reservation']) ? $response['reservation'] : null;
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventory/getReservations
     */
    public function getReservations(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'reservations',
            'query' => $query
        ]);
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
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Inventories/getCumulatedInventories
     */
    public function getCumulatedInventories(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'cumulatedInventories',
            'query' => $query
        ]);
    }

    /**
     * @param array $parentCatalogs
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/ParentCatalogs/createParentCatalogs
     */
    public function addParentCatalogs(array $parentCatalogs, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'parentCatalogs',
            'json' => true,
            'body' => ['parentCatalogs' => $parentCatalogs],
            'query' => $query
        ]);
    }

    /**
     * @param string $catalogCode
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Catalogs/deleteCatalog
     */
    public function deleteCatalog($catalogCode, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'catalogs/' . $catalogCode,
            'query' => $query
        ]);
    }

    /**
     * @param array $catalogs
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Catalogs/createCatalogs
     */
    public function addCatalogs(array $catalogs, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'catalogs',
            'json' => true,
            'body' => ['catalogs' => $catalogs],
            'query' => $query
        ]);
    }

    /**
     * @param string $code - catalog code
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Catalogs/getCatalog
     */
    public function getCatalog($code, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'catalogs/' . $code,
            'query' => $query
        ]);

        return isset($response['catalog']) ? $response['catalog'] : null;
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
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Catalogs/getCatalogs
     */
    public function getCatalogs(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'catalogs',
            'query' => $query
        ]);
    }

    /**
     * @param string $code - catalog code
     * @param array $catalog
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/catalog-crud.yaml#/Catalogs/updateCatalog
     */
    public function updateCatalog($code, array $catalog, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'catalogs/' . $code,
            'body' => $catalog,
            'query' => $query
        ]);
    }
}
