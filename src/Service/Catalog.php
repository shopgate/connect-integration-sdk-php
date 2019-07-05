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
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Inventory;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\ShopgateSdk;

class Catalog
{
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
     * @param Category\Create[] $categories
     * @param array             $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addCategories(array $categories, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['categories' => $categories],
                'query'       => $query,
                // direct
                'service'     => 'catalog',
                'path'        => 'categories',
                // async
                'entity'      => 'category',
                'action'      => 'create',
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
     */
    public function updateCategory($code, Category\Update $category, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => $category,
                'query'       => $query,
                // direct
                'method'      => 'post',
                'service'     => 'catalog',
                'path'        => 'categories/' . $code,
                // async
                'entity'      => 'category',
                'action'      => 'update',
                'entityId'    => $code,
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
     */
    public function deleteCategory($code, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
                // direct
                'method'      => 'delete',
                'service'     => 'catalog',
                'path'        => 'categories/' . $code,
                // async
                'entity'      => 'category',
                'action'      => 'delete',
                'entityId'    => $code,
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
     */
    public function getCategories(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = \GuzzleHttp\json_encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'categories',
                'query'   => $query,
            ]
        );
        $response = json_decode($response->getBody(), true);

        $categories = [];
        foreach ($response['categories'] as $category) {
            $categories[] = new Category\Get($category);
        }
        $response['meta']       = new Meta($response['meta']);
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
     */
    public function addProducts(array $products, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products',
                'entity'      => 'product',
                'action'      => 'create',
                'body'        => ['products' => $products],
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
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
     */
    public function updateProduct($code, Product\Update $product, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $code,
                'entityId'    => $code,
                'entity'      => 'product',
                'action'      => 'update',
                'body'        => $product,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
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
     */
    public function deleteProduct($code, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'products/' . $code,
                'entity'      => 'product',
                'action'      => 'delete',
                'entityId'    => $code,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
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
     */
    public function getProducts(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = \GuzzleHttp\json_encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'products',
                'query'   => $query,
            ]
        );
        $response = json_decode($response->getBody(), true);

        $products = [];
        foreach ($response['products'] as $product) {
            $products[] = new Product\Get($product);
        }
        $response['meta']     = new Meta($response['meta']);
        $response['products'] = $products;

        return new Product\GetList($response);
    }

    /**
     * @param string  $code
     * @param array  $query
     *
     * @return Product\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getProduct($code, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'products/' . $code,
                'query'   => $query
            ]
        );
        $response = json_decode($response->getBody(), true);

        return new Product\Get($response['product']);
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
     */
    public function addAttributes(array $attributes, array $query = [])
    {
        $requestAttributes = [];
        foreach ($attributes as $attribute) {
            $requestAttributes[] = $attribute->toArray();
        }

        return $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['attributes' => $requestAttributes],
                'query'       => $query,
                // direct
                'service'     => 'catalog',
                'path'        => 'attributes',
                // async
                'entity'      => 'attribute',
                'action'      => 'create',
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
     */
    public function getAttributes(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = \GuzzleHttp\json_encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'attributes',
                'query'   => $query,
            ]
        );
        $response = json_decode($response->getBody(), true);

        $attributes = [];
        foreach ($response['attributes'] as $attribute) {
            $attributes[] = new Attribute\Get($attribute);
        }
        $response['meta']       = new Meta($response['meta']);
        $response['attributes'] = $attributes;

        return new Attribute\GetList($response);
    }

    /**
     * @param string $attributeCode
     * @param array $query
     *
     * @return Attribute\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getAttribute($attributeCode, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'attributes/' . $attributeCode,
                'query'   => $query,
            ]
        );

        $response = json_decode($response->getBody(), true);

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
     */
    public function updateAttribute($attributeCode, Attribute\Update $attribute, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode,
                'entity'      => 'attribute',
                'query'       => $query,
                // direct only
                'action'      => 'update',
                'body'        => $attribute,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $attributeCode,
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
     */
    public function deleteAttribute($attributeCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'attributes/' . $attributeCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $attributeCode,
                'query'       => $query,
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
     */
    public function addAttributeValue(
        $attributeCode,
        array $attributeValues,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode . '/values/',
                'entity'      => 'attributes',
                'action'      => 'create',
                'body'        => ['values' => $attributeValues],
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
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
     */
    public function updateAttributeValue(
        $attributeCode,
        $attributeValueCode,
        AttributeValue\Update $attributeValue,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
                'entity'      => 'attribute',
                'action'      => 'update',
                'body'        => $attributeValue,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'entityId'    => $attributeCode,
                'query'       => $query,
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
     */
    public function deleteAttributeValue($attributeCode, $attributeValueCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param Inventory\Create[] $inventories
     * @param array              $query
     *
     * @return ResponseInterface
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addInventories(array $inventories, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'inventories',
                'entity'      => 'inventory',
                'action'      => 'create',
                'body'        => ['inventories' => $inventories],
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param Inventory\Delete[] $inventories
     * @param array              $query
     *
     * @return ResponseInterface
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteInventories(array $inventories, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'inventories',
                'entity'      => 'inventory',
                'body'        => ['inventories' => $inventories],
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
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
     */
    public function updateInventories($inventories, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'patch',
                'path'        => 'inventories',
                'entity'      => 'inventory',
                'body'        => ['inventories' => $inventories],
                'action'      => 'update',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
            ]
        );
    }
}
