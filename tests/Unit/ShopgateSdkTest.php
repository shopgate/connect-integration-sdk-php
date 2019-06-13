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
use Shopgate\ConnectSdk\Dto\Catalog\Category\Create as CategoryCreate;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Update as CategoryUpdate;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Create as ProductCreate;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Update as ProductUpdate;
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
        $mock->expects($this->exactly(12))->method('request');
        $subjectUnderTest->getCatalogService()->updateCategory(1, new CategoryUpdate());
        $subjectUnderTest->getCatalogService()->updateCategory(1, new CategoryUpdate(), ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->addCategories([new CategoryCreate()]);
        $subjectUnderTest->getCatalogService()->addCategories([new CategoryCreate()], ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->deleteCategory('1');
        $subjectUnderTest->getCatalogService()->deleteCategory('1', ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->updateProduct(1, new ProductUpdate());
        $subjectUnderTest->getCatalogService()->updateProduct(1, new ProductUpdate(), ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->addProducts([new ProductCreate()]);
        $subjectUnderTest->getCatalogService()->addProducts([new ProductCreate()], ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->deleteProduct('1');
        $subjectUnderTest->getCatalogService()->deleteProduct('1', ['requestType' => 'direct']);
    }

    /**
     * Testing direct calls and service rewrites for categories
     */
    public function testDirectCatalogCategoryActions()
    {
        $entityId         = 1;
        $defaultMeta      = ['service' => 'catalog'];
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
        $mock->expects($this->exactly(3))->method('request')->withConsecutive(
            [
                $this->equalTo('post'),
                $this->equalTo('categories/' . $entityId),
                ['query' => $defaultMeta, 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('categories/' . $entityId),
                ['query' => $defaultMeta, 'json' => '[]']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('categories'),
                ['query' => $defaultMeta, 'json' => '{"categories":[[]]}']
            ]
        );

        $subjectUnderTest->getCatalogService()->updateCategory($entityId, new CategoryUpdate(), ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->deleteCategory($entityId, ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->addCategories([new CategoryCreate()], ['requestType' => 'direct']);
    }

    /**
     * Testing direct calls and service rewrites for products
     */
    public function testDirectCatalogProductActions()
    {
        $entityId         = 1;
        $defaultMeta      = ['service' => 'catalog'];
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
        $mock->expects($this->exactly(3))->method('request')->withConsecutive(
            [
                $this->equalTo('post'),
                $this->equalTo('products/' . $entityId),
                ['query' => $defaultMeta, 'json' => '{}']
            ],
            [
                $this->equalTo('delete'),
                $this->equalTo('products/' . $entityId),
                ['query' => $defaultMeta, 'json' => '[]']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('products'),
                ['query' => $defaultMeta, 'json' => '{"products":[[]]}']
            ]
        );

        $subjectUnderTest->getCatalogService()->updateProduct($entityId, new ProductUpdate(), ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->deleteProduct($entityId, ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->addProducts([new ProductCreate()], ['requestType' => 'direct']);
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
