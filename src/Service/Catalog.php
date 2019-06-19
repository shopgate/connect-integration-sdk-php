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
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception;
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
                'query'       => $meta,
            ]
        );
    }

    /**
     * @param array $meta
     *
     * @todo-sg: supposedly needs more than just limit/offset as there are many query methods defined, ask Pascal
     * @return Category\GetList
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function deleteProduct($code, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function addAttributes(array $attributes, array $meta = [])
    {
        $requestAttributes = [];
        foreach ($attributes as $attribute) {
            $requestAttributes[] = $attribute->toArray();
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
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
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
     * @param string $attributeCode
     * @param string $localeCode
     *
     * @return Attribute\Get
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function getAttribute($attributeCode, $localeCode = '')
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'catalog',
                'method'  => 'get',
                'path'    => 'attributes/' . $attributeCode,
                'query'   => [
                    'localeCode' => $localeCode,
                ],
            ]
        );

        $response = json_decode($response->getBody(), true);

        return new Attribute\Get($response['attribute']);
    }

    /**
     * @param string           $attributeCode
     * @param Attribute\Update $payload
     * @param array            $meta
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function updateAttribute($attributeCode, Attribute\Update $payload, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                // general
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode,
                'entity'      => 'attribute',
                // direct only
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $attributeCode,
            ]
        );
    }

    /**
     * @param string $attributeCode
     * @param array  $meta
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function deleteAttribute($attributeCode, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'attributes/' . $attributeCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $attributeCode,
                'query'       => $meta,
            ]
        );
    }

    /**
     * @param string                  $attributeCode
     * @param AttributeValue\Create[] $attributeValues
     * @param array                   $meta
     *
     * @return ResponseInterface
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function addAttributeValue(
        $attributeCode,
        array $attributeValues,
        array $meta = []
    ) {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode . '/values/',
                'entity'      => 'attributes',
                'action'      => 'create',
                'body'        => ['values' => $attributeValues],
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }

    /**
     * @param string                $attributeCode
     * @param string                $attributeValueCode
     * @param AttributeValue\Update $payload
     * @param array                 $meta
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function updateAttributeValue(
        $attributeCode,
        $attributeValueCode,
        AttributeValue\Update $payload,
        array $meta = []
    ) {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
                'entity'      => 'attribute',
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'entityId'    => $attributeCode,
            ]
        );
    }

    /**
     * @param string $attributeCode
     * @param string $attributeValueCode
     * @param array  $meta
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function deleteAttributeValue($attributeCode, $attributeValueCode, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'delete',
                'path'        => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($meta['requestType'])
                    ? $meta['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
            ]
        );
    }
}
