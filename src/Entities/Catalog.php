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
use Shopgate\ConnectSdk\DTO\Catalog\Category\Create;
use Shopgate\ConnectSdk\DTO\Catalog\Category\Get as Category;
use Shopgate\ConnectSdk\DTO\Catalog\Category\GetList as CategoryList;
use Shopgate\ConnectSdk\DTO\Catalog\Product;
use Shopgate\ConnectSdk\DTO\Meta;
use Shopgate\ConnectSdk\ClientInterface;

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
     * @param Create[] $categories
     * @param array      $meta
     *
     * @return ResponseInterface
     */
    public function addCategories(array $categories, array $meta = [])
    {
        $requestCategories = [];
        foreach($categories as $category) {
            try {
                $requestCategories[] = $category->toArray();
            } catch (InvalidDataTypeException $e) {
                // TODO: handle exception
            }
        }

        return $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => $meta['requestType'],
                'body'        => json_encode(['categories' => $requestCategories]),
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
     * @param string   $entityId
     * @param Create $payload
     * @param array    $meta
     *
     * @return ResponseInterface
     */
    public function updateCategory($entityId, Create $payload, array $meta = [])
    {
        return $this->client->doRequest(
            [
                // general
                'requestType' => $meta['requestType'],
                'body'        => $payload->toJson(),
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
                'requestType' => $meta['requestType'],
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
     * @return CategoryList
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

        $categories = array();
        foreach ($response['categories'] as $category) {
            $categories[] = new Category($category);
        }
        $response['meta'] = new Meta($response['meta']);
        $response['categories'] = $categories;

        return new CategoryList($response);
    }

    /**
     * @param Product[] $products
     * @param array     $meta
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
                'requestType' => $meta['requestType']
            ]
        );
    }

    /**
     * @param string  $entityId
     * @param Product $payload
     * @param array   $meta
     *
     * @return ResponseInterface
     */
    public function updateProduct($entityId, Product $payload, array $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $entityId,
                'entity'      => 'product',
                'action'      => 'update',
                'body'        => $payload->toJson(),
                'requestType' => $meta['requestType']
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
                'requestType' => $meta['requestType']
            ]
        );
    }

    public function getProducts(array $meta = [])
    {
        //todo-sg: finish up
    }
}
