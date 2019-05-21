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

namespace Shopgate\ConnectSdk\Tests\Unit\Services\Events;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Http\GuzzleClient;
use Shopgate\ConnectSdk\Services\Events\Client;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Base;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Category as CategoryDto;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product as ProductDto;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Services\Events\Client
 */
class ClientTest extends TestCase
{
    /**
     * @var MockBuilder
     */
    protected $httpClient;

    /**
     * Set up needed objects
     */
    protected function setUp()
    {
        $this->httpClient = $this->getMockBuilder(GuzzleClient::class)->disableOriginalConstructor();
    }

    /**
     * Tests the magic getter for catalog
     *
     * @covers \Shopgate\ConnectSdk\Services\Events\Client
     * @covers \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Base
     * @covers \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog
     */
    public function testGetCatalog()
    {
        $subjectUnderTest = new Client([]);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(Catalog::class, $subjectUnderTest->catalog);
    }

    /**
     * Checking the basic routing, more complicated tests should be done per class
     */
    public function testGetCatalogActions()
    {
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new Client(['http_client' => $mock]);
        /** @noinspection PhpParamsInspection */
        $mock->expects($this->exactly(12))->method('request');
        $subjectUnderTest->catalog->updateCategory(1, new CategoryDto());
        $subjectUnderTest->catalog->updateCategory(1, new CategoryDto(), [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->createCategory(new CategoryDto());
        $subjectUnderTest->catalog->createCategory(new CategoryDto(), [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->deleteCategory('1');
        $subjectUnderTest->catalog->deleteCategory('1', [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->updateProduct(1, new ProductDto());
        $subjectUnderTest->catalog->updateProduct(1, new ProductDto(), [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->createProduct(new ProductDto());
        $subjectUnderTest->catalog->createProduct(new ProductDto(), [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->deleteProduct('1');
        $subjectUnderTest->catalog->deleteProduct('1', [Base::KEY_TYPE => Base::SYNC]);
    }

    /**
     * Testing direct calls and service rewrites for categories
     */
    public function testDirectCatalogCategoryActions()
    {
        $entityId         = 1;
        $defaultMeta      = ['service' => 'catalog', Base::KEY_TYPE => Base::SYNC];
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new Client(['http_client' => $mock]);
        /** @noinspection PhpParamsInspection */
        $mock->expects($this->exactly(6))->method('request')->withConsecutive(
            [
                $this->equalTo('post'),
                $this->equalTo('categories/' . $entityId),
                ['query' => $defaultMeta, 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('categories/' . $entityId),
                ['query' => $defaultMeta, 'json' => '{}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('categories'),
                ['query' => $defaultMeta, 'json' => '{"categories":[[]]}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('categories/' . $entityId),
                ['query' => ['service' => 'test', Base::KEY_TYPE => Base::SYNC], 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('categories/' . $entityId),
                ['query' => ['service' => 'test', Base::KEY_TYPE => Base::SYNC], 'json' => '{}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('categories'),
                ['query' => ['service' => 'test', Base::KEY_TYPE => Base::SYNC], 'json' => '{"categories":[[]]}']
            ]
        );

        $payload = new CategoryDto();
        $subjectUnderTest->catalog->updateCategory($entityId, $payload, [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->deleteCategory($entityId, [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->createCategory($payload, [Base::KEY_TYPE => Base::SYNC]);

        // rewriting service via direct call
        $subjectUnderTest->catalog->updateCategory(
            $entityId,
            $payload,
            ['service' => 'test', Base::KEY_TYPE => Base::SYNC]
        );
        $subjectUnderTest->catalog->deleteCategory($entityId, ['service' => 'test', Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->createCategory($payload, ['service' => 'test', Base::KEY_TYPE => Base::SYNC]);
    }

    /**
     * Testing direct calls and service rewrites for products
     */
    public function testDirectCatalogProductActions()
    {
        $entityId         = 1;
        $defaultMeta      = ['service' => 'catalog', Base::KEY_TYPE => Base::SYNC];
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new Client(['http_client' => $mock]);
        /** @noinspection PhpParamsInspection */
        $mock->expects($this->exactly(6))->method('request')->withConsecutive(
            [
                $this->equalTo('post'),
                $this->equalTo('products/' . $entityId),
                ['query' => $defaultMeta, 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('products/' . $entityId),
                ['query' => $defaultMeta, 'json' => '{}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('products'),
                ['query' => $defaultMeta, 'json' => '{"products":[[]]}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('products/' . $entityId),
                ['query' => ['service' => 'test', Base::KEY_TYPE => Base::SYNC], 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('products/' . $entityId),
                ['query' => ['service' => 'test', Base::KEY_TYPE => Base::SYNC], 'json' => '{}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('products'),
                ['query' => ['service' => 'test', Base::KEY_TYPE => Base::SYNC], 'json' => '{"products":[[]]}']
            ]
        );

        $payload = new ProductDto();
        $subjectUnderTest->catalog->updateProduct($entityId, $payload, [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->deleteProduct($entityId, [Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->createProduct($payload, [Base::KEY_TYPE => Base::SYNC]);

        // rewriting service via direct call
        $subjectUnderTest->catalog->updateProduct(
            $entityId,
            $payload,
            ['service' => 'test', Base::KEY_TYPE => Base::SYNC]
        );
        $subjectUnderTest->catalog->deleteProduct($entityId, ['service' => 'test', Base::KEY_TYPE => Base::SYNC]);
        $subjectUnderTest->catalog->createProduct($payload, ['service' => 'test', Base::KEY_TYPE => Base::SYNC]);
    }
}
