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

namespace Shopgate\ConnectSdk\Tests\Unit;

use GuzzleHttp\HandlerStack;
use kamermans\OAuth2\Persistence\NullTokenPersistence;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\DTO\Catalog\Category\Create as CategoryDto;
use Shopgate\ConnectSdk\DTO\Catalog\Product as ProductDto;
use Shopgate\ConnectSdk\Http\GuzzleClient;
use Shopgate\ConnectSdk\ShopgateSdk;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\ShopgateSdk
 */
class ShopgateSdkTest extends TestCase
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
     * Checking the basic routing, more complicated tests should be done per class
     */
    public function testGetCatalogActions()
    {
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new ShopgateSdk(
            [
                'http_client'  => $mock,
                'clientSecret' => '',
                'clientId'     => '',
                'merchantCode' => 'x'
            ]
        );
        /** @noinspection PhpParamsInspection */
        $mock->expects($this->exactly(14))->method('request');
        $subjectUnderTest->catalog->updateCategory(1, new CategoryDto());
        $subjectUnderTest->catalog->updateCategory(1, new CategoryDto(), ['requestType' => 'direct']);
        $subjectUnderTest->catalog->addCategories([new CategoryDto()]);
        $subjectUnderTest->catalog->addCategories([new CategoryDto()], ['requestType' => 'direct']);
        $subjectUnderTest->catalog->deleteCategory('1');
        $subjectUnderTest->catalog->deleteCategory('1', ['requestType' => 'direct']);
        $subjectUnderTest->catalog->updateProduct(1, new ProductDto());
        $subjectUnderTest->catalog->updateProduct(1, new ProductDto(), ['requestType' => 'direct']);
        $subjectUnderTest->catalog->createProduct(new ProductDto());
        $subjectUnderTest->catalog->createProduct(new ProductDto(), ['requestType' => 'direct']);
        $subjectUnderTest->catalog->deleteProduct('1');
        $subjectUnderTest->catalog->deleteProduct('1', ['requestType' => 'direct']);
        $subjectUnderTest->catalog->getProduct(['requestType' => 'direct']);
        $subjectUnderTest->catalog->getProduct([]);
    }

    /**
     * Testing direct calls and service rewrites for categories
     */
    public function testDirectCatalogCategoryActions()
    {
        $entityId         = 1;
        $defaultMeta      = ['service' => 'catalog', 'requestType' => 'direct'];
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new ShopgateSdk(
            [
                'http_client'  => $mock,
                'clientSecret' => '',
                'clientId'     => '',
                'merchantCode' => 'x'
            ]
        );
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
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('categories/' . $entityId),
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('categories'),
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{"categories":[[]]}']
            ]
        );

        $payload = new CategoryDto();
        $subjectUnderTest->catalog->updateCategory($entityId, $payload, ['requestType' => 'direct']);
        $subjectUnderTest->catalog->deleteCategory($entityId, ['requestType' => 'direct']);
        $subjectUnderTest->catalog->createCategory($payload, ['requestType' => 'direct']);

        // rewriting service via direct call
        $subjectUnderTest->catalog->updateCategory(
            $entityId,
            $payload,
            ['service' => 'test', 'requestType' => 'direct']
        );
        $subjectUnderTest->catalog->deleteCategory($entityId, ['service' => 'test', 'requestType' => 'direct']);
        $subjectUnderTest->catalog->createCategory($payload, ['service' => 'test', 'requestType' => 'direct']);
    }

    /**
     * Testing direct calls and service rewrites for products
     */
    public function testDirectCatalogProductActions()
    {
        $entityId         = 1;
        $defaultMeta      = ['service' => 'catalog', 'requestType' => 'direct'];
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new ShopgateSdk(
            [
                'http_client'  => $mock,
                'clientSecret' => '',
                'clientId'     => '',
                'merchantCode' => 'x',
            ]
        );
        /** @noinspection PhpParamsInspection */
        $mock->expects($this->exactly(8))->method('request')->withConsecutive(
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
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('products/' . $entityId),
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{}']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('products'),
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{"products":[[]]}']
            ],
            [
                $this->equalTo('get'),
                $this->equalTo('products'),
                ['query' => ['service' => 'test', 'requestType' => 'direct'], 'json' => '{}']
            ],
            [
                $this->equalTo('get'),
                $this->equalTo('products'),
                ['query' => ['service' => 'test'], 'json' => '{}']
            ]
        );

        $payload = new ProductDto();
        $subjectUnderTest->catalog->updateProduct($entityId, $payload, ['requestType' => 'direct']);
        $subjectUnderTest->catalog->deleteProduct($entityId, ['requestType' => 'direct']);
        $subjectUnderTest->catalog->createProduct($payload, ['requestType' => 'direct']);

        // rewriting service via direct call
        $subjectUnderTest->catalog->updateProduct(
            $entityId,
            $payload,
            ['service' => 'test', 'requestType' => 'direct']
        );
        $subjectUnderTest->catalog->deleteProduct($entityId, ['service' => 'test', 'requestType' => 'direct']);
        $subjectUnderTest->catalog->createProduct($payload, ['service' => 'test', 'requestType' => 'direct']);
        $subjectUnderTest->catalog->getProduct(['service' => 'test', 'requestType' => 'direct']);
        $subjectUnderTest->catalog->getProduct(['service' => 'test']);
    }

    public function testSettingCustomPersistence()
    {
        /** @var MockObject|GuzzleClient $mock */
        $mock             = new GuzzleClient(
            [
                'clientSecret' => '',
                'clientId'     => '',
                'merchantCode' => 'x',
                'oauth'        => ['base_uri' => '', 'storage_path' => '']
            ]
        );
        $subjectUnderTest = new ShopgateSdk(
            [
                'http_client'  => $mock,
                'clientSecret' => '',
                'clientId'     => '',
                'merchantCode' => 'x'
            ]
        );
        /** @var HandlerStack $handler */
        $handler = $mock->getConfig('handler');
        $out     = (string) $handler;
        $this->assertNotFalse(strpos($out, "> 1) Name: 'OAuth2'"));
        $subjectUnderTest->setStorage(new NullTokenPersistence());
        $out2 = (string) $handler;
        $this->assertFalse(strpos($out2, "> 1) Name: 'OAuth2'"));
        $this->assertNotFalse(strpos($out2, "> 1) Name: 'OAuth2.custom'"));
    }
}
