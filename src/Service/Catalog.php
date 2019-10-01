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
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

class Catalog
{
    const SERVICE_CATALOG = 'catalog';

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
     * @param Category\Create[] $categories
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
    public function addCategories(array $categories, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'method' => 'post',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'json' => ['categories' => $categories],
                'query' => $query,
                // direct
                'service' => self::SERVICE_CATALOG,
                'path' => 'categories',
                // async
                'entity' => 'category',
                'action' => 'create',
            ]
        );
    }

    /**
     * @param string          $code
     * @param Category\Update $category
     * @param array           $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateCategory($code, Category\Update $category, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'json' => $category,
                'query' => $query,
                // direct
                'method' => 'post',
                'service' => self::SERVICE_CATALOG,
                'path' => 'categories/' . $code,
                // async
                'entity' => 'category',
                'action' => 'update',
                'entityId' => $code,
            ]
        );
    }

    /**
     * @param string $code
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteCategory($code, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query' => $query,
                // direct
                'method' => 'delete',
                'service' => self::SERVICE_CATALOG,
                'path' => 'categories/' . $code,
                // async
                'entity' => 'category',
                'action' => 'delete',
                'entityId' => $code,
            ]
        );
    }

    /**
     * @param array $query
     *
     * @return Category\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getCategories(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'categories',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $categories = [];
        foreach ($response['categories'] as $category) {
            $categories[] = new Category\Get($category);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['categories'] = $categories;

        return new Category\GetList($response);
    }

    /**
     * @param Product\Create[] $products
     * @param array            $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addProducts(array $products, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'products',
                'entity' => 'product',
                'action' => 'create',
                'json' => ['products' => $products],
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string         $code
     * @param Product\Update $product
     * @param array          $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateProduct($code, Product\Update $product, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'products/' . $code,
                'entityId' => $code,
                'entity' => 'product',
                'action' => 'update',
                'json' => $product,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $code
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteProduct($code, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'delete',
                'path' => 'products/' . $code,
                'entity' => 'product',
                'action' => 'delete',
                'entityId' => $code,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param array $query
     *
     * @return Product\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getProducts(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'products',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $products = [];
        foreach ($response['products'] as $product) {
            $products[] = new Product\Get($product);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['products'] = $products;

        return new Product\GetList($response);
    }

    /**
     * @param string $code - product code
     * @param array  $query
     *
     * @return Product\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getProduct($code, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'products/' . $code,
                'query' => $query
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Product\Get($response['product']);
    }

    /**
     * @param string $code - product code
     * @param array  $query
     *
     * @return ProductDescriptions\Get
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getProductDescriptions($code, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'products/' . $code . '/descriptions',
                'query' => $query
            ]
        );
        $response = json_decode($response->getBody(), true);

        return new ProductDescriptions\Get($response);
    }

    /**
     * @param Attribute\Create[] $attributes
     * @param array              $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addAttributes(array $attributes, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'method' => 'post',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'json' => ['attributes' => $attributes],
                'query' => $query,
                // direct
                'service' => self::SERVICE_CATALOG,
                'path' => 'attributes',
                // async
                'entity' => 'attribute',
                'action' => 'create',
            ]
        );
    }

    /**
     * @param array $query
     *
     * @return Attribute\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getAttributes(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'attributes',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $attributes = [];
        foreach ($response['attributes'] as $attribute) {
            $attributes[] = new Attribute\Get($attribute);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['attributes'] = $attributes;

        return new Attribute\GetList($response);
    }

    /**
     * @param string $attributeCode
     * @param array  $query
     *
     * @return Attribute\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getAttribute($attributeCode, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'attributes/' . $attributeCode,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Attribute\Get($response['attribute']);
    }

    /**
     * @param string           $attributeCode
     * @param Attribute\Update $attribute
     * @param array            $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateAttribute($attributeCode, Attribute\Update $attribute, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'attributes/' . $attributeCode,
                'entity' => 'attribute',
                'query' => $query,
                // direct only
                'action' => 'update',
                'json' => $attribute,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId' => $attributeCode,
            ]
        );
    }

    /**
     * @param string $attributeCode
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteAttribute($attributeCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'delete',
                'path' => 'attributes/' . $attributeCode,
                'entity' => 'attribute',
                'action' => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId' => $attributeCode,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string                  $attributeCode
     * @param AttributeValue\Create[] $attributeValues
     * @param array                   $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addAttributeValue(
        $attributeCode,
        array $attributeValues,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'attributes/' . $attributeCode . '/values/',
                'entity' => 'attributes',
                'action' => 'create',
                'json' => ['values' => $attributeValues],
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string                $attributeCode
     * @param string                $attributeValueCode
     * @param AttributeValue\Update $attributeValue
     * @param array                 $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateAttributeValue(
        $attributeCode,
        $attributeValueCode,
        AttributeValue\Update $attributeValue,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
                'entity' => 'attribute',
                'action' => 'update',
                'json' => $attributeValue,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'entityId' => $attributeCode,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $attributeCode
     * @param string $attributeValueCode
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteAttributeValue($attributeCode, $attributeValueCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'delete',
                'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
                'entity' => 'attributeValue',
                'entityId' => $attributeValueCode,
                'action' => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param Inventory\Create[] $inventories
     * @param array              $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addInventories(array $inventories, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'inventories',
                'entity' => 'inventory',
                'action' => 'create',
                'json' => ['inventories' => $inventories],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param Inventory\Delete[] $inventories
     * @param array              $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteInventories(array $inventories, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'delete',
                'path' => 'inventories',
                'entity' => 'inventory',
                'json' => ['inventories' => $inventories],
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param Inventory\Update[] $inventories
     * @param array              $query
     *
     * @return ResponseInterface
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateInventories($inventories, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'patch',
                'path' => 'inventories',
                'entity' => 'inventory',
                'json' => ['inventories' => $inventories],
                'action' => 'update',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param Reservation\Create[] $reservations
     * @param array                $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addReservations(array $reservations, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'reservations',
                'entity' => 'reservation',
                'action' => 'create',
                'json' => ['reservations' => $reservations],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param array $codes
     * @param array $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteReservations(array $codes, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'delete',
                'path' => 'reservations',
                'entity' => 'reservation',
                'json' => ['codes' => $codes],
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string             $reservationCode
     * @param Reservation\Update $reservation
     * @param array              $query
     *
     * @return ResponseInterface
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateReservation($reservationCode, $reservation, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'path' => 'reservations/' . $reservationCode,
                'entity' => 'reservation',
                'json' => $reservation,
                'action' => 'update',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $reservationCode
     * @param array  $query
     *
     * @return Reservation\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getReservation($reservationCode, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'reservations/' . $reservationCode,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Reservation\Get($response['reservation']);
    }

    /**
     * @param array $query
     *
     * @return Reservation\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getReservations(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'reservations',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $reservations = [];
        foreach ($response['reservations'] as $reservation) {
            $reservations[] = new Reservation\Get($reservation);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['reservations'] = $reservations;

        return new Reservation\GetList($response);
    }

    /**
     * @param ParentCatalog\Create[] $parentCatalogs
     * @param array                  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addParentCatalogs(array $parentCatalogs, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json' => ['parentCatalogs' => $parentCatalogs],
                'query' => $query,
                'path' => 'parentCatalogs',
            ]
        );
    }

    /**
     * @param string $catalogCode
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteCatalog($catalogCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
                'path' => 'catalogs/' . $catalogCode,
            ]
        );
    }

    /**
     * @param CatalogDto\Create[] $catalogs
     * @param array               $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addCatalogs(array $catalogs, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json' => ['catalogs' => $catalogs],
                'query' => $query,
                'path' => 'catalogs',
            ]
        );
    }

    /**
     * @param string $code - catalog code
     * @param array  $query
     *
     * @return CatalogDto\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getCatalog($code, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'catalogs/' . $code,
                'query' => $query
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new CatalogDto\Get($response['catalog']);
    }

    /**
     * @param array $query
     *
     * @return CatalogDto\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getCatalogs(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CATALOG,
                'method' => 'get',
                'path' => 'catalogs',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $catalogs = [];
        foreach ($response['catalogs'] as $catalog) {
            $catalogs[] = new Reservation\Get($catalog);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['catalogs'] = $catalogs;

        return new CatalogDto\GetList($response);
    }
}
