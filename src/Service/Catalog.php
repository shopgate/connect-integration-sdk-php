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
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Helper\Value;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;

class Catalog
{
    const NAME = 'catalog';

    /** @var ClientInterface */
    private $client;

    /** @var Json */
    private $jsonHelper;

    /**
     * @param ClientInterface $client
     * @param Json $jsonHelper
     */
    public function __construct(ClientInterface $client, Json $jsonHelper)
    {
        $this->client = $client;
        $this->jsonHelper = $jsonHelper;
    }

    #####################################################################################################
    # Parent Catalog
    #####################################################################################################

    /**
     * @param array[] $parentCatalogs
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDA-create-new-parent-catalogs
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

    #####################################################################################################
    # Catalog
    #####################################################################################################

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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDE-get-catalogs
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
     * @param array[] $catalogs
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDI-create-catalogs
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDM-get-catalog
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
     * @param string $code - catalog code
     * @param array $catalog
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDQ-update-catalog
     */
    public function updateCatalog($code, array $catalog, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'catalogs/' . $code,
            'body' => $catalog,
            'query' => $query
        ]);
    }

    /**
     * @param string $catalogCode
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDU-delete-catalog
     */
    public function deleteCatalog($catalogCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'catalogs/' . $catalogCode,
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Attribute
    #####################################################################################################

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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDY-get-attributes
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
     * @param array[] $attributes
     * @param array $query
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDc-create-attributes
     */
    public function addAttributes(array $attributes, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publish('entityCreated', 'attribute', $attributes);
        }

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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDg-get-attribute
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
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MDk-update-attribute
     */
    public function updateAttribute($attributeCode, array $attribute, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publish(
                'entityUpdated',
                'attribute',
                [['code' => $attributeCode] + $attribute]
            );
        }

        return $this->client->request([
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
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTA-delete-attribute
     */
    public function deleteAttribute($attributeCode, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publishEntityDeleted('attribute', $attributeCode);
        }

        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'attributes/' . $attributeCode,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array[] $attributeValues
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTE-set-attribute-values
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
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTI-update-attribute-value
     */
    public function updateAttributeValue($attributeCode, $attributeValueCode, array $attributeValue, array $query = [])
    {
        return $this->client->request([
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
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTM-delete-attribute-value
     */
    public function deleteAttributeValue($attributeCode, $attributeValueCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Category
    #####################################################################################################

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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTQ-get-categories
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
     * @param array[] $categories
     * @param array $query
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTU-create-categories
     */
    public function addCategories(array $categories, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publish(
                'entityCreated',
                'category',
                $this->spreadCatalogCode($categories, $query)
            );
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
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTY-update-category
     */
    public function updateCategory($code, array $category, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publish(
                'entityUpdated',
                'category',
                $this->spreadCatalogCode([['code' => $code] + $category], $query)
            );
        }

        return $this->client->request([
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
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MTc-delete-category
     */
    public function deleteCategory($code, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publishEntityDeleted('category', $code);
        }

        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'categories/' . $code,
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Product
    #####################################################################################################

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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MjM-get-products
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
     * @param array[] $products
     * @param array $query
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MjQ-create-products
     */
    public function addProducts(array $products, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publish(
                'entityCreated',
                'product',
                $this->spreadCatalogCode($products, $query)
            );
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5Mjc-get-product
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
     * @param string $code
     * @param array $product
     * @param array $query
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5Mjg-update-product
     */
    public function updateProduct($code, array $product, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publish(
                'entityUpdated',
                'product',
                $this->spreadCatalogCode([['code' => $code] + $product], $query)
            );
        }

        return $this->client->request([
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
     * @param bool $async
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5Mjk-delete-product
     */
    public function deleteProduct($code, array $query = [], $async = true)
    {
        if ($async) {
            return $this->client->publishEntityDeleted('product', $code);
        }

        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'products/' . $code,
            'query' => $query
        ]);
    }

    /**
     * @param string $code
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MzA-get-product-descriptions
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
     * @param string $code
     * @param array $selectedOptions
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MzE-validate-variant
     */
    public function validateOptionSelection($code, array $selectedOptions, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'products/' . $code . '/validate',
            'body' => ['selectedOptions' => $selectedOptions],
            'query' => $query
        ]);
    }

    #####################################################################################################
    # Inventory
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MzI-get-inventories
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MzM-set-inventories
     */
    public function addInventories(array $inventories, array $query = [])
    {
        // the event receiver does not yet support entities of type inventory

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
     * @param array[] $inventories
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MzQ-increment-decrement-inventory
     */
    public function changeInventories(array $inventories, array $query = [])
    {
        // the event receiver does not yet support entities of type inventory

        return $this->client->request([
            'service' => self::NAME,
            'method' => 'patch',
            'path' => 'inventories',
            'body' => ['inventories' => $inventories],
            'query' => $query
        ]);
    }

    /**
     * @param array $inventories
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
     * @deprecated use changeInventories()
     */
    public function updateInventories(array $inventories, array $query = [])
    {
        return $this->changeInventories($inventories, $query);
    }

    /**
     * @param array $inventories
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5MzU-delete-inventories
     */
    public function deleteInventories(array $inventories, array $query = [])
    {
        // the event receiver does not yet support entities of type inventory

        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'inventories',
            'body' => ['inventories' => $inventories],
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6NDE5ODU5NDA-get-location-inventories
     */
    public function getLocalInventories(array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'localInventories',
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5Mzg-get-reservations
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
     * @param array[] $reservations
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5Mzk-reserve-inventory
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
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5NDA-delete-inventory-reservations
     */
    public function deleteReservations(array $codes, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'reservations',
            'body' => ['codes' => $codes],
            'query' => $query
        ]);
    }

    /**
     * @param array $fulfillmentOrderNumbers
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5NDE-settle-reservations
     */
    public function settleReservations(array $fulfillmentOrderNumbers, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'reservations/settle',
            'body' => ['fulfillmentOrderNumbers' => $fulfillmentOrderNumbers],
            'query' => $query
        ]);
    }

    /**
     * @param string $reservationCode
     * @param array $reservation
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5NDI-update-reservation
     */
    public function updateReservation($reservationCode, $reservation, array $query = [])
    {
        return $this->client->request([
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5NDM-get-reservation
     */
    public function getReservation($reservationCode, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'reservations/' . $reservationCode,
            'query' => $query
        ]);

        return isset($response['reservation']) ? $response['reservation'] : null;
    }

    /**
     * @param array[] $productAndLocationCodes
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5NDQ-get-product-inventories
     */
    public function getProductInventories(array $productAndLocationCodes, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'productInventories',
            'body' => $productAndLocationCodes
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
     * @see https://docs.shopgate.com/docs/retail-red/b3A6MzU3ODQ5NDU-get-cumulated-inventories
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

    private function spreadCatalogCode($entities, $query)
    {
        return empty($query['catalogCode'])
            ? $entities
            : Value::addValue($entities, $query['catalogCode'], 'catalogCode');
    }
}
