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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\ClientInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Create as CategoryCreate;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Update as CategoryUpdate;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Create as ProductCreate;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Update as ProductUpdate;
use Shopgate\ConnectSdk\Service\BulkImport;
use Shopgate\ConnectSdk\Service\Catalog;
use Shopgate\ConnectSdk\ShopgateSdk;

class ShopgateSdkTest extends TestCase
{
    /** @var ShopgateSdk */
    private $subjectUnderTest;

    /** @var ClientInterface|MockObject */
    private $client;

    /**
     * Set up needed objects
     */
    protected function setUp()
    {
        $this->client = $this->getMockBuilder(ClientInterface::class)->getMock();
    }

    public function testShouldConstructWithGivenClient()
    {
        $subjectUnderTest = new ShopgateSdk(['client' => $this->client]);

        $this->assertSame($this->client, $subjectUnderTest->getClient());
    }

    public function testShouldConstructWithNewInstanceOfClient()
    {
        $subjectUnderTest = new ShopgateSdk(['clientId'     => 'test',
                                             'clientSecret' => 'secret',
                                             'merchantCode' => 'TM2'
        ]);

        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(ClientInterface::class, $subjectUnderTest->getClient());
    }

    public function testShouldConstructWithGivenServices()
    {
        $catalog          = $this->getMockBuilder(Catalog::class)->disableOriginalConstructor()->getMock();
        $bulkImport       = $this->getMockBuilder(BulkImport::class)->disableOriginalConstructor()->getMock();
        $subjectUnderTest = new ShopgateSdk([
            'client'   => $this->client,
            'services' => [
                'catalog'    => $catalog,
                'bulkImport' => $bulkImport
            ]
        ]);

        $this->assertSame($catalog, $subjectUnderTest->getCatalogService());
        $this->assertSame($bulkImport, $subjectUnderTest->getBulkImportService());
    }

    public function testShouldConstructWithNewInstancesOfServices()
    {
        $subjectUnderTest = new ShopgateSdk(['client' => $this->client]);

        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(Catalog::class, $subjectUnderTest->getCatalogService());

        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(BulkImport::class, $subjectUnderTest->getBulkImportService());
    }


    /********************************************************************************************
     * Keeping the below for peeking; tests should be moved to their respective service classes
     ********************************************************************************************/

    /**
     * Testing direct calls and service rewrites for categories
     */
    public function testDirectCatalogCategoryActions()
    {
        $this->markTestSkipped('Move to CatalogTest');

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
                ['query' => $defaultMeta + ['requestType' => 'direct'], 'json' => '[]']
            ],
            [
                $this->equalTo('post'),
                $this->equalTo('categories'),
                ['query' => $defaultMeta, 'json' => '{"categories":[[]]}']
            ]
        );

        $subjectUnderTest->getCatalogService()->updateCategory(
            $entityId,
            new CategoryUpdate(),
            ['requestType' => 'direct']
        );
        $subjectUnderTest->getCatalogService()->deleteCategory($entityId, ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->addCategories([new CategoryCreate()], ['requestType' => 'direct']);
    }

    /**
     * Testing direct calls and service rewrites for products
     */
    public function testDirectCatalogProductActions()
    {
        $this->markTestSkipped('Move to CatalogTest');

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

        $subjectUnderTest->getCatalogService()->updateProduct(
            $entityId,
            new ProductUpdate(),
            ['requestType' => 'direct']
        );
        $subjectUnderTest->getCatalogService()->deleteProduct($entityId, ['requestType' => 'direct']);
        $subjectUnderTest->getCatalogService()->addProducts([new ProductCreate()], ['requestType' => 'direct']);
    }
}
