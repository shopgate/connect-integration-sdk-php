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

use Shopgate\ConnectSdk\DTO\Catalog\Category;
use Shopgate\ConnectSdk\DTO\Catalog\Product;
use Shopgate\ConnectSdk\IClient;

class Catalog
{
    /** @var IClient */
    private $client;

    /**
     * @param IClient $client
     */
    public function __construct(IClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Category[] $categories
     * @param array      $meta
     *
     * @return mixed
     */
    public function addCategories(array $categories, $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'categories',
                'entity'      => 'category',
                'action'      => 'create',
                'body'        => ['categories' => $categories],
                'requestType' => $meta['requestType']
            ]
        );
    }

    /**
     * @param string   $entityId
     * @param Category $payload
     * @param array    $meta
     *
     * @return mixed
     */
    public function updateCategory($entityId, Category $payload, $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'categories/' . $entityId,
                'entity'      => 'category',
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => $meta['requestType']
            ]
        );
    }

    /**
     * @param string $entityId
     * @param array  $meta
     *
     * @return mixed
     */
    public function deleteCategory($entityId, $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'categories/' . $entityId,
                'entity'      => 'category',
                'action'      => 'delete',
                'requestType' => $meta['requestType']
            ]
        );
    }

    /**
     * @param array $meta
     *
     * @todo-sg: supposedly needs more than just limit/offset as there are many query methods defined, ask Pascal
     * @return mixed
     */
    public function getCategories($meta = [])
    {
        if (isset($meta['filters'])) {
            $meta['filters'] = \GuzzleHttp\json_encode($meta['filters']);
        }

        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'get',
                'path'        => 'categories',
                'requestType' => $meta['requestType'],
                'query'       => $meta
            ]
        );
    }

    /**
     * @param Product[] $products
     * @param array     $meta
     *
     * @return mixed
     */
    public function addProducts(array $products, $meta = [])
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
     * @return mixed
     */
    public function updateProduct($entityId, Product $payload, $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $entityId,
                'entity'      => 'product',
                'action'      => 'update',
                'body'        => $payload,
                'requestType' => $meta['requestType']
            ]
        );
    }

    /**
     * @param string $entityId
     * @param array  $meta
     *
     * @return mixed
     */
    public function deleteProduct($entityId, $meta = [])
    {
        //todo-sg: test
        return $this->client->doRequest(
            [
                'service'     => 'catalog',
                'method'      => 'post',
                'path'        => 'products/' . $entityId,
                'entity'      => 'product',
                'action'      => 'delete',
                'requestType' => $meta['requestType']
            ]
        );
    }
}
