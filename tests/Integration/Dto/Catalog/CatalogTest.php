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

/** @noinspection PhpUnhandledExceptionInspection */

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Catalog;

use Shopgate\ConnectSdk\Dto\Catalog\Catalog\Get;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Tests\Integration\AbstractCatalogTest;

class CatalogTest extends AbstractCatalogTest
{
    /**
     * @throws Exception
     */
    public function testCreateCatalog()
    {
        // Arrange
        $catalog = $this->provideSampleCatalog();

        // Act
        $this->sdk->getCatalogService()->addCatalogs([$catalog]);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATALOG,
            [$catalog->getCode()]
        );

        // Assert
        $catalog = $this->sdk->getCatalogService()->getCatalog($catalog->getCode());

        $this->assertEquals('TestCatalogCode', $catalog->getCode());
    }

    /**
     * @throws Exception
     */
    public function testGetCatalogShouldThrowNotFoundException()
    {
        // Arrange
        $catalog = $this->provideSampleCatalog();

        // Assert
        $this->expectException(NotFoundException::class);

        // Act
        $this->sdk->getCatalogService()->getCatalog($catalog->getCode());
    }

    /**
     * @depends testCreateCatalog
     * @depends testGetCatalogShouldThrowNotFoundException
     *
     * @throws Exception
     *
     * @doesNotPerformAssertions
     */
    public function testDeleteCatalog()
    {
        // Arrange
        $catalog = $this->provideSampleCatalog();
        $this->sdk->getCatalogService()->addCatalogs([$catalog]);

        // Act
        $this->sdk->getCatalogService()->deleteCatalog($catalog->getCode());

        // Assert
        try {
            $this->sdk->getCatalogService()->getCatalog($catalog->getCode());
        } catch (NotFoundException $exception) {
            return;
        }

        $this->fail('NotFoundException was not thrown!');
    }

    /**
     * @depends testCreateCatalog
     *
     * @throws Exception
     */
    public function testGetCatalogs()
    {
        // Arrange
        $catalog = $this->provideSampleCatalog();
        $this->sdk->getCatalogService()->addCatalogs([$catalog]);

        // Act
        $catalogs = $this->sdk->getCatalogService()->getCatalogs();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATALOG,
            [$catalog->getCode()]
        );

        // Assert
        $this->assertCount(3, $catalogs->getCatalogs());
        $this->assertInstanceOf(Get::class, $catalogs->getCatalogs()[0]);
    }

    /**
     * @param int      $limit
     * @param int      $offset
     * @param int      $expectedCatalogCount
     * @param string[] $expectedCatalogCodes
     *
     * @throws Exception
     *
     * @depends      testCreateCatalog
     *
     * @dataProvider provideCategoryLimitCases
     */
    public function testCategoryLimit($limit, $offset, $expectedCatalogCount, $expectedCatalogCodes)
    {
        $this->markTestSkipped('catalog service is currently not respecting parameters for limit and offset');

        // Arrange
        $sampleCatalog = $this->provideSampleCatalog();
        $sampleCatalogs = $this->defaultCatalogs();
        $sampleCatalogs[] = $sampleCatalog;

        $this->sdk->getCatalogService()->addCatalogs([$sampleCatalog]);

        $parameters = [];
        if (isset($limit)) {
            $parameters['limit'] = $limit;
        }
        if (isset($offset)) {
            $parameters['offset'] = $offset;
        }

        // Act
        $catalogs = $this->sdk->getCatalogService()->getCatalogs($parameters);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATALOG,
            [$sampleCatalog->getCode()]
        );

        // Assert
        /** @noinspection PhpParamsInspection */

        $catalogCodes = [];
        foreach ($catalogs->getCatalogs() as $catalog) {
            $catalogCodes[] = $catalog->getCode();
        }

        $this->assertCount($expectedCatalogCount, $catalogs->getCatalogs());
        $this->assertEquals($expectedCatalogCodes, $catalogCodes);
        if (isset($limit)) {
            $this->assertEquals($limit, $catalogs->getMeta()->getLimit());
        }
        if (isset($offset)) {
            $this->assertEquals($offset, $catalogs->getMeta()->getOffset());
        }
    }

    /**
     * @return array
     */
    public function provideCategoryLimitCases()
    {
        return [
            'get the second' => [
                'limit' => 1,
                'offset' => 1,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::SAMPLE_CATALOG_CODE_NON_DEFAULT
                ]
            ],
            'get the first' => [
                'limit' => 1,
                'offset' => 0,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::SAMPLE_CATALOG_CODE
                ]
            ],
            'get two' => [
                'limit' => 2,
                'offset' => 0,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::SAMPLE_CATALOG_CODE,
                    self::SAMPLE_CATALOG_CODE_NON_DEFAULT
                ]
            ],
            'limit 1' => [
                'limit' => 1,
                'offset' => null,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::SAMPLE_CATALOG_CODE
                ]
            ],
            'limit 2' => [
                'limit' => 2,
                'offset' => null,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::SAMPLE_CATALOG_CODE,
                    self::SAMPLE_CATALOG_CODE_NON_DEFAULT
                ]
            ],
            'offset 1' => [
                'limit' => null,
                'offset' => 1,
                'expectedCount' => 1,
                'expectedCodes' => [self::SAMPLE_CATALOG_CODE]
            ],
            'offset 2' => [
                'limit' => null,
                'offset' => 2,
                'expectedCount' => 0,
                'expectedCodes' => []
            ],
            'no entities found' => [
                'limit' => 1,
                'offset' => 2,
                'expectedCount' => 0,
                'expectedCodes' => []
            ]
        ];
    }
}
