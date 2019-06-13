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

namespace Shopgate\ConnectSdk\Entities;

use Dto\Exceptions\InvalidDataTypeException;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\ClientInterface;
use Shopgate\ConnectSdk\DTO\Catalog\Attribute;
use Shopgate\ConnectSdk\DTO\Catalog\Category;
use Shopgate\ConnectSdk\DTO\Catalog\Product;
use Shopgate\ConnectSdk\DTO\Meta;
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
     * @param array             $meta
     *
     * @return ResponseInterface
     */
    public function addCategories(array $categories, array $meta = [])
    {
        return $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['categories' => $categories],
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
     * @param Category\Update $payload
     * @param array           $meta
     *
     * @return ResponseInterface
     */
    public function updateCategory($code, Category\Update $payload, array $meta = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => $payload,
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
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function deleteCategory($code, array $meta = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
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
     * @param array $meta
     *
     * @todo-sg: supposedly needs more than just limit/offset as there are many query methods defined, ask Pascal
     * @return Category\GetList
     */
    public function getCategories(array $meta = [])
    {
        if (isset($meta['filters'])) {
            $meta['filters'] = \GuzzleHttp\json_encode($meta['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'categories',
                'query'   => $meta,
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
     * @param array            $meta
     *
     * @return ResponseInterface
     */
    public function addProducts(array $products, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products',
                'entity'      => 'product',
                'action'      => 'create',
                'body'        => ['products' => $products],
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param string         $code
     * @param Product\Update $payload
     * @param array          $meta
     *
     * @return ResponseInterface
     */
    public function updateProduct($code, Product\Update $payload, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $code,
                'entityId'    => $code,
                'entity'      => 'product',
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param string $code
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function deleteProduct($code, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $code,
                'entity'      => 'product',
                'action'      => 'delete',
                'entityId'    => $code,
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param array $meta
     *
     * @return Product\GetList
     */
    public function getProducts(array $meta = [])
    {
        if (isset($meta['filters'])) {
            $meta['filters'] = \GuzzleHttp\json_encode($meta['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'products',
                'query'   => $meta,
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
     * @param string  $fields
     * @param boolean $getOriginalImageUrls
     *
     * @return Product\Get
     */
    public function getProduct($code, $fields = '', $getOriginalImageUrls = false)
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'products/' . $code,
                'query'   => [
                    'fields'               => $fields,
                    'getOriginalImageUrls' => json_encode($getOriginalImageUrls),
                ],
            ]
        );
        $response = json_decode($response->getBody(), true);

        return new Product\Get($response['product']);
    }

    /**
     * @param Attribute\Create[] $attributes
     * @param array              $meta
     *
     * @return ResponseInterface
     */
    public function addAttributes(array $attributes, array $meta = [])
    {
        $requestAttributes = [];
        foreach ($attributes as $attribute) {
            try {
                $requestAttributes[] = $attribute->toArray();
            } catch (InvalidDataTypeException $e) {
                // TODO: handle exception
            }
        }

        return $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['attributes' => $requestAttributes],
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
     * @param array $meta
     *
     * @todo-sg: supposedly needs more than just limit/offset as there are many query methods defined, ask Pascal
     * @return Attribute\GetList
     */
    public function getAttributes(array $meta = [])
    {
        if (isset($meta['filters'])) {
            $meta['filters'] = \GuzzleHttp\json_encode($meta['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'attributes',
                'query'   => $meta,
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
     * @param string $code
     * @param string $localeCode
     *
     * @return Attribute\Get
     */
    public function getAttribute($code, $localeCode = '')
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'attributes/' . $code,
                'query'   => [
                    'localeCode' => $localeCode,
                ],
            ]
        );

        $response = json_decode($response->getBody(), true);

        return new Attribute\Get($response['attribute']);
    }

    /**
     * @param string           $code
     * @param Attribute\Update $payload
     * @param array            $meta
     *
     * @return ResponseInterface
     */
    public function updateAttribute($code, Attribute\Update $payload, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $code,
                'entity'      => 'attribute',
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param string $code
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function deleteAttribute($code, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'attributes/' . $code,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param string                 $code
     * @param string                 $valueCode
     * @param Attribute\Value\Update $payload
     * @param array                  $meta
     *
     * @return ResponseInterface
     */
    public function updateAttributeValue(
        $code,
        $valueCode,
        Attribute\Value\Update $payload,
        array $meta = []
    ) {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $code . '/values/' . $valueCode,
                'entity'      => 'attribute',
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param string $code
     * @param string $attributeValueCode
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function deleteAttributeValue($code, $attributeValueCode, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'attributes/' . $code . '/values/' . $attributeValueCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }
}
