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
use Shopgate\ConnectSdk\DTO\Catalog\Attribute\Get as Attribute;
use Shopgate\ConnectSdk\DTO\Catalog\Attribute\GetList as AttributeList;
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
                'requestType' => isset($meta['requestType']) ? $meta['requestType'] : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['categories' => $categories],
                // direct
                'service'     => 'catalog',
                'path'        => 'categories',
                // async
                'entity'      => 'category',
                'action'      => 'create'
            ]
        );
    }

    /**
     * @param string          $entityId
     * @param Category\Update $payload
     * @param array           $meta
     *
     * @return ResponseInterface
     */
    public function updateCategory($entityId, Category\Update $payload, array $meta = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($meta['requestType']) ? $meta['requestType'] : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => $payload,
                // direct
                'method'      => 'post',
                'service'     => 'catalog',
                'path'        => 'categories/' . $entityId,
                // async
                'entity'      => 'category',
                'action'      => 'update',
                'entityId'    => $entityId
            ]
        );
    }

    /**
     * @param string $entityId
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function deleteCategory($entityId, array $meta = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => isset($meta['requestType']) ? $meta['requestType'] : ShopgateSdk::REQUEST_TYPE_EVENT,
                // direct
                'method'      => 'delete',
                'service'     => 'catalog',
                'path'        => 'categories/' . $entityId,
                // async
                'entity'      => 'category',
                'action'      => 'delete',
                'entityId'    => $entityId
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
                'query'   => $meta
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
//        $requestProducts = [];
//        foreach ($products as $product) {
//            try {
//                $requestProducts[] = $product->toArray();
//            } catch (InvalidDataTypeException $e) {
//                // TODO: handle exception
//            }
//        }

        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products',
                'entity'      => 'product',
                'action'      => 'create',
                'body'        => ['products' => $products],
                'requestType' => isset($meta['requestType']) ? $meta['requestType'] : ShopgateSdk::REQUEST_TYPE_EVENT
            ]
        );
    }

    /**
     * @param string  $entityId
     * @param Product\Update $payload //TODO: change Create to Update once it exists
     * @param array   $meta
     *
     * @return ResponseInterface
     */
    public function updateProduct($entityId, Product\Create $payload, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $entityId,
                'entityId'    => $entityId,
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
     * @param string $entityId
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function deleteProduct($entityId, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $entityId,
                'entity'      => 'product',
                'action'      => 'delete',
                'entityId'    => $entityId,
                'requestType' => isset($meta['requestType']) ? $meta['requestType'] : ShopgateSdk::REQUEST_TYPE_EVENT
            ]
        );
    }

    public function getProducts(array $meta = [])
    {
        //todo-sg: finish up
    }

    /**
     * @param Attribute[] $attributes
     * @param array       $meta
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
                'requestType' => isset($meta['requestType']) ? $meta['requestType'] : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['attributes' => $requestAttributes],
                // direct
                'service'     => 'catalog',
                'path'        => 'attributes',
                // async
                'entity'      => 'attribute',
                'action'      => 'create'
            ]
        );
    }

    /**
     * @param array $meta
     *
     * @todo-sg: supposedly needs more than just limit/offset as there are many query methods defined, ask Pascal
     * @return AttributeList
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
                'query'   => $meta
            ]
        );
        $response = json_decode($response->getBody(), true);

        $attributes = [];
        foreach ($response['attributes'] as $attribute) {
            $attributes[] = new Attribute($attribute);
        }
        $response['meta']       = new Meta($response['meta']);
        $response['attributes'] = $attributes;

        return new AttributeList($response);
    }
}