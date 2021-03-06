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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Catalog;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Name;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Tests\Integration\CatalogUtility;

class CategoryTest extends CatalogUtility
{
    /**
     * @throws Exception
     */
    public function testCreateCategoryDirect()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);

        // Act
        $this->createCategories(
            $sampleCategories,
            ['requestType' => 'direct']
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, $sampleCategoryCodes);

        // Assert
        $categories = $this->getCategories($sampleCategoryCodes);
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    /**
     * @param Category\Create[] $sampleCategories
     * @param array             $meta
     *
     * @return ResponseInterface
     * @throws RequestException
     * @throws Exception
     *
     */
    private function createCategories(array $sampleCategories, array $meta = [])
    {
        return $this->sdk->getCatalogService()->addCategories($sampleCategories, $meta);
    }

    /**
     * @param array $categoryCodes
     * @param array $meta
     *
     * @return Category\GetList
     * @throws Exception
     *
     */
    private function getCategories($categoryCodes = [], $meta = [])
    {
        return $this->sdk->getCatalogService()->getCategories(
            array_merge(
                ['filters' => ['code' => ['$in' => $categoryCodes]]],
                $meta
            )
        );
    }

    /**
     * @depends testCreateCategoryDirect
     *
     * @throws Exception
     */
    public function testGetCategories()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, ['requestType' => 'direct']);

        // Act
        $categories = $this->sdk->getCatalogService()->getCategories();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATEGORY,
            $this->getCategoryCodes($sampleCategories)
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testGetCategoriesWithSpecificCatalogCode()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, [
            'requestType' => 'direct',
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT,
        ]);

        // Act
        $categories = $this->sdk->getCatalogService()->getCategories([
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT,
        ]);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATEGORY,
            $this->getCategoryCodes($sampleCategories),
            self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    /**
     * @param int      $limit
     * @param int      $offset
     * @param int      $expectedCategoryCount
     * @param string[] $expectedCategoryCodes
     *
     * @throws Exception
     *
     * @depends      testCreateCategoryDirect
     *
     * @dataProvider provideCategoryLimitCases
     */
    public function testCategoryLimit($limit, $offset, $expectedCategoryCount, $expectedCategoryCodes)
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);
        $this->createCategories(
            $sampleCategories,
            ['requestType' => 'direct']
        );

        $parameters = [];
        if (isset($limit)) {
            $parameters['limit'] = $limit;
        }
        if (isset($offset)) {
            $parameters['offset'] = $offset;
        }

        // Act
        $categories = $this->getCategories($sampleCategoryCodes, $parameters);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, $sampleCategoryCodes);

        // Assert
        /** @noinspection PhpParamsInspection */

        $categoryCodes = [];
        foreach ($categories->getCategories() as $category) {
            $categoryCodes[] = $category->getCode();
        }

        $this->assertCount($expectedCategoryCount, $categories->getCategories());
        $this->assertEquals($expectedCategoryCodes, $categoryCodes);
        if (isset($limit)) {
            $this->assertEquals($limit, $categories->getMeta()->getLimit());
        }
        if (isset($offset)) {
            $this->assertEquals($offset, $categories->getMeta()->getOffset());
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
                    self::CATEGORY_CODE_SECOND
                ]
            ],
            'get the first' => [
                'limit' => 1,
                'offset' => 0,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::CATEGORY_CODE
                ]
            ],
            'get two' => [
                'limit' => 2,
                'offset' => 0,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::CATEGORY_CODE,
                    self::CATEGORY_CODE_SECOND
                ]
            ],
            'limit 1' => [
                'limit' => 1,
                'offset' => null,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::CATEGORY_CODE
                ]
            ],
            'limit 2' => [
                'limit' => 2,
                'offset' => null,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::CATEGORY_CODE,
                    self::CATEGORY_CODE_SECOND
                ]
            ],
            'offset 1' => [
                'limit' => null,
                'offset' => 1,
                'expectedCount' => 1,
                'expectedCodes' => [self::CATEGORY_CODE_SECOND]
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

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testUpdateCategoryDirect()
    {
        // Arrange
        $this->sdk->getCatalogService()->addCategories(
            [
                $this->provideSampleCreateCategory(
                    self::CATEGORY_CODE,
                    'Integration Test Category 1',
                    1
                )
            ],
            ['requestType' => 'direct']
        );
        $newName = 'Renamed Product (Direct)';
        $category = new Category\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $this->sdk->getCatalogService()->updateCategory(
            self::CATEGORY_CODE,
            $category,
            ['requestType' => 'direct']
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [self::CATEGORY_CODE]);

        // Assert
        $categories = $this->getCategories([self::CATEGORY_CODE]);
        $updatedCategory = $categories->getCategories()[0];
        $this->assertEquals($newName, $updatedCategory->getName());
    }

    /**
     * @throws Exception
     */
    public function testUseAddMethods()
    {
        // Arrange
        $name = new Category\Dto\Name();
        $name->add('en-us', 'Name EN');
        $name->add('de-de', 'Name DE');

        $description = new Category\Dto\Description();
        $description->add('en-us', 'Description EN');
        $description->add('de-de', 'Description DE');

        $url = new Category\Dto\Url();
        $url->add('en-us', 'http://google.com');
        $url->add('de-de', 'http://google.de');

        $image = new Category\Dto\Image();
        $image->add('en-us', 'http://image.com');
        $image->add('de-de', 'http://image.de');

        $category = new Category\Create();
        $category->setCode(self::CATEGORY_CODE)
            ->setSequenceId(1)
            ->setName($name)
            ->setDescription($description)
            ->setUrl($url)
            ->setImage($image)
            ->setExternalUpdateDate('2019-12-15T00:00:00.000Z')
            ->setStatus(Category\Create::STATUS_ACTIVE);

        // Act
        $this->createCategories(
            [$category],
            ['requestType' => 'direct']
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [self::CATEGORY_CODE]);

        // Assert
        $categories = $this->getCategories([self::CATEGORY_CODE]);
        $category = $categories->getCategories()[0];

        $this->assertEquals('Name EN', $category->getName());
        $this->assertEquals('Description EN', $category->getDescription());
        $this->assertEquals('http://google.com', $category->getUrl());
        $this->assertTrue((bool)strpos($category->getImage(), 'http://image.com'));
    }

    /**
     * @throws Exception
     */
    public function testLocale()
    {
        // Arrange
        $name = new Category\Dto\Name();
        $name->add('en-us', 'Name EN');
        $name->add('de-de', 'Name DE');

        $description = new Category\Dto\Description();
        $description->add('en-us', 'Description EN');
        $description->add('de-de', 'Description DE');

        $url = new Category\Dto\Url();
        $url->add('en-us', 'http://google.com');
        $url->add('de-de', 'http://google.de');

        $image = new Category\Dto\Image();
        $image->add('en-us', 'http://image.com');
        $image->add('de-de', 'http://image.de');

        $category = new Category\Create();
        $category->setCode(self::CATEGORY_CODE)
            ->setSequenceId(1)
            ->setName($name)
            ->setDescription($description)
            ->setUrl($url)
            ->setImage($image)
            ->setExternalUpdateDate('2019-12-15T00:00:00.000Z')
            ->setStatus(Category\Create::STATUS_ACTIVE);

        // Act
        $this->createCategories(
            [$category],
            ['requestType' => 'direct']
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [self::CATEGORY_CODE]);

        // Assert
        $categoriesDe = $this->getCategories([self::CATEGORY_CODE], ['localeCode' => 'de-de']);
        $categoryDe = $categoriesDe->getCategories()[0];

        $this->assertEquals('Name DE', $categoryDe->getName());
        $this->assertEquals('Description DE', $categoryDe->getDescription());
        $this->assertEquals('http://google.de', $categoryDe->getUrl());
        $this->assertTrue((bool)strpos($categoryDe->getImage(), 'http://image.de'));

        $categoriesEn = $this->getCategories([self::CATEGORY_CODE], ['localeCode' => 'en-us']);
        $categoryEn = $categoriesEn->getCategories()[0];

        $this->assertEquals('Name EN', $categoryEn->getName());
        $this->assertEquals('Description EN', $categoryEn->getDescription());
        $this->assertEquals('http://google.com', $categoryEn->getUrl());
        $this->assertTrue((bool)strpos($categoryEn->getImage(), 'http://image.com'));
    }

    /**
     * @param array  $updateCategoryData
     * @param string $expectedValue
     *
     * @throws Exception
     *
     * @depends      testCreateCategoryDirect
     * @depends      testGetCategories
     *
     * @dataProvider provideUpdateCategoryData
     */
    public function testUpdateCategoryPropertyDirect(array $updateCategoryData, $expectedValue)
    {
        // Arrange
        $this->sdk->getCatalogService()->addCategories(
            [
                $this->provideSampleCreateCategory(
                    self::PARENT_CATEGORY_CODE,
                    'Parent Integration Test Category',
                    1,
                    new Category\Dto\Image(['en-us' => 'http://www.google.de/parent.png']),
                    new Category\Dto\Url(['en-us' => 'https://www.google.de/parent']),
                    'test parent description'
                ),
                $this->provideSampleCreateCategory(
                    self::CATEGORY_CODE,
                    'Integration Test Category 1',
                    1,
                    new Category\Dto\Image(['en-us' => 'http://www.google.de/image.png']),
                    new Category\Dto\Url(['en-us' => 'https://www.google.de']),
                    'test description',
                    null,
                    '2019-12-15T00:00:00.000Z',
                    Category::STATUS_INACTIVE
                )
            ],
            ['requestType' => 'direct']
        );
        $category = new Category\Update($updateCategoryData);

        // Act
        $this->sdk->getCatalogService()->updateCategory(
            self::CATEGORY_CODE,
            $category,
            ['requestType' => 'direct']
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATEGORY,
            [
                self::CATEGORY_CODE,
                self::PARENT_CATEGORY_CODE
            ]
        );

        // Assert
        $categories = $this->getCategories([self::CATEGORY_CODE], ['getOriginalImageUrls' => true]);
        $updatedCategory = $categories->getCategories()[0];
        $updatedKey = array_keys($updateCategoryData)[0];
        $this->assertEquals($expectedValue, $updatedCategory->get($updatedKey));
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function provideUpdateCategoryData()
    {
        return [
            'name' => [
                'updateCategoryData' => [
                    'name' => new Name(['en-us' => 'Updated Name']),
                ],
                'expectedValue' => 'Updated Name'
            ],
            'description' => [
                'updateCategoryData' => [
                    'description' => new Category\Dto\Description(['en-us' => 'Updated Description']),
                ],
                'expectedValue' => 'Updated Description'
            ],
            'image' => [
                'updateCategoryData' => [
                    'image' => new Category\Dto\Image(['en-us' => 'http://updated.com/image.png']),
                ],
                'expectedValue' => 'http://updated.com/image.png'
            ],
            'url' => [
                'updateCategoryData' => [
                    'url' => new Category\Dto\Url(['en-us' => 'http://updated.url.com']),
                ],
                'expectedValue' => 'http://updated.url.com'
            ],
            'parentCategoryCode' => [
                'updateCategoryData' => [
                    'parentCategoryCode' => self::PARENT_CATEGORY_CODE,
                ],
                'expectedValue' => self::PARENT_CATEGORY_CODE
            ],
            'externalUpdateDate' => [
                'updateCategoryData' => [
                    'externalUpdateDate' => '2019-12-21T00:00:00.000Z',
                ],
                'expectedValue' => '2019-12-21T00:00:00.000Z'
            ],
            'status' => [
                'updateCategoryData' => [
                    'status' => Category::STATUS_ACTIVE,
                ],
                'expectedValue' => Category::STATUS_ACTIVE
            ]
        ];
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testDeleteCategoryDirect()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, ['requestType' => 'direct']);

        // Act
        foreach ($this->getCategoryCodes($sampleCategories) as $categoryCode) {
            $this->sdk->getCatalogService()->deleteCategory(
                $categoryCode,
                ['requestType' => 'direct']
            );
        }

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        /** @noinspection PhpParamsInspection */
        $this->assertCount(0, $categories->getCategories());
    }

    /**
     * @param array            $categoryData
     * @param RequestException $expectedException
     *
     * @throws Exception
     *
     * @dataProvider provideCreateCategoryWithMissingRequiredFields
     */
    public function testCreateCategoryDirectWithMissingRequiredFields(array $categoryData, $expectedException)
    {
        // Arrange
        $category = new Category\Create($categoryData);

        // Act
        try {
            $this->createCategories(
                [$category],
                ['requestType' => 'direct']
            );
        } catch (RequestException $exception) {
            // Assert
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @return array
     */
    public function provideCreateCategoryWithMissingRequiredFields()
    {
        return [
            'missing name' => [
                'categoryData' => [
                    'code' => 'category-test-code',
                    'sequenceId' => 1006
                ],
                'expectedException' => new RequestException(400)
            ],
            'missing code' => [
                'categoryData' => [
                    'name' => 'Test Category',
                    'sequenceId' => 1006
                ],
                'expectedException' => new RequestException(400)
            ],
            'missing sequenceId' => [
                'categoryData' => [
                    'name' => 'Test Category',
                    'code' => 'category-test-code',
                ],
                'expectedException' => new RequestException(400)
            ],
        ];
    }

    /**
     * @param array     $categoryData
     * @param Exception $expectedException
     *
     * @throws Exception
     *
     * @dataProvider provideCreateCategoryWithInvalidDataTypes
     */
    public function testCreateCategoryDirectWithInvalidDataTypes($categoryData, $expectedException)
    {
        // Act
        try {
            $category = new Category\Create($categoryData);
            $this->createCategories(
                [$category],
                ['requestType' => 'direct']
            );
        } catch (RequestException $exception) {
            // Assert
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        } catch (InvalidDataTypeException $invalidDataTypeException) {
            $this->assertInstanceOf(get_class($expectedException), $invalidDataTypeException);

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function provideCreateCategoryWithInvalidDataTypes()
    {
        return [
            'wrong code data type' => [
                'categoryData' => [
                    'name' => ['en-us' => 'Test Category'],
                    'code' => 123456,
                    'sequenceId' => 1006
                ],
                'expectedException' => new InvalidDataTypeException()
            ],
            'wrong sequenceId data type' => [
                'categoryData' => [
                    'name' => ['en-us' => 'Test Category'],
                    'code' => 'category-test-code',
                    'sequenceId' => '1006'
                ],
                'expectedException' => new InvalidDataTypeException()
            ],
            'wrong parentCategoryCode data type' => [
                'categoryData' => [
                    'name' => ['en-us' => 'Test Category'],
                    'code' => 'category-test-code',
                    'sequenceId' => 1006,
                    'parentCategoryCode' => 12345
                ],
                'expectedException' => new InvalidDataTypeException()
            ],
            'wrong description type' => [
                'categoryData' => [
                    'name' => ['en-us' => 'Test Category'],
                    'code' => 'category-test-code',
                    'sequenceId' => 1006,
                    'description' => new Category\Dto\Description(['en-US' => 12345])
                ],
                'expectedException' => new RequestException(400)
            ],
        ];
    }

    /**
     * @depends testCreateCategoryDirect
     *
     * @throws Exception
     */
    public function testUpdateCategoryWithoutAnyDataGiven()
    {
        // Arrange
        $categoryCode = 'example-code';
        $existingCategory = $this->provideSampleCreateCategory(
            $categoryCode,
            'test category',
            1,
            new Category\Dto\Image(['en-us' => 'http://www.google.de']),
            new Category\Dto\Url(['en-us' => 'http://www.google.de/image.png']),
            'test description'
        );
        $this->sdk->getCatalogService()->addCategories(
            [$existingCategory],
            ['requestType' => 'direct']
        );
        $updateCategory = new Category\Update();

        // Act
        $response = $this->sdk->getCatalogService()->updateCategory(
            $categoryCode,
            $updateCategory,
            ['requestType' => 'direct']
        );

        // Assert
        $this->assertEquals(204, $response->getStatusCode());

        // Cleanup
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [$categoryCode]);
    }

    /**
     * @throws Exception
     */
    public function testUpdateCategoryWithNonExistingCategory()
    {
        // Arrange
        $nonExistentCategoryCode = 'non-existent';
        $category = $this->provideSampleUpdateCategory('test non existent category');

        // Assert
        $this->expectException(NotFoundException::class);

        // Act
        $this->sdk->getCatalogService()->updateCategory(
            $nonExistentCategoryCode,
            $category,
            ['requestType' => 'direct']
        );
    }

    /**
     * @param string             $name
     * @param Category\Dto\Image $image
     * @param Category\Dto\Url   $url
     * @param string             $description
     * @param string             $parentCategoryCode
     *
     * @return Category\Update
     *
     * @throws Exception
     */
    private function provideSampleUpdateCategory(
        $name = null,
        $image = null,
        $url = null,
        $description = null,
        $parentCategoryCode = null
    ) {
        $category = new Category\Update();

        if ($name) {
            $translatedName = new Category\Dto\Name(['en-us' => $name]);
            $category->setName($translatedName);
        }
        if ($url) {
            $category->setUrl($url);
        }
        if ($description) {
            $translatedDescription = new Category\Dto\Description($description);
            $category->setDescription($translatedDescription);
        }
        if ($image) {
            $category->setImage($image);
        }
        if ($parentCategoryCode) {
            $category->setParentCategoryCode($parentCategoryCode);
        }

        return $category;
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testCreateCategoryEvent()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);

        // Act
        $response = $this->createCategories($sampleCategories);
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, $sampleCategoryCodes);

        // Assert
        $categories = $this->getCategories($sampleCategoryCodes);
        $this->assertEquals(202, $response->getStatusCode());
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategoriesWithSpecificCatalogCode
     *
     * @throws Exception
     */
    public function testCreateCategoryEventInSpecificCatalogCode()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);

        // Act
        $response = $this->createCategories($sampleCategories, [
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATEGORY,
            $sampleCategoryCodes,
            self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        );

        // Assert
        $categories = $this->getCategories($sampleCategoryCodes, [
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);
        $this->assertEquals(202, $response->getStatusCode());
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testUpdateCategoryEvent()
    {
        // Arrange
        $this->sdk->getCatalogService()->addCategories(
            [
                $this->provideSampleCreateCategory(self::CATEGORY_CODE, 'Integration Test Category 1', 1)
            ],
            ['requestType' => 'direct']
        );
        $newName = 'Renamed Product (Event)';
        $updatedCategory = new Category\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $response = $this->sdk->getCatalogService()->updateCategory(self::CATEGORY_CODE, $updatedCategory);
        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, [self::CATEGORY_CODE]);

        // Assert
        $categories = $this->getCategories([self::CATEGORY_CODE]);
        $updatedCategory = $categories->getCategories()[0];
        $this->assertEquals(202, $response->getStatusCode());
        $this->assertEquals($newName, $updatedCategory->getName());
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testDeleteCategoryEvent()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, ['requestType' => 'direct']);
        $responses = [];

        // Act
        foreach ($this->getCategoryCodes($sampleCategories) as $categoryCode) {
            $responses[] = $this->sdk->getCatalogService()->deleteCategory($categoryCode);
        }

        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        /** @noinspection PhpParamsInspection */
        $this->assertCount(0, $categories->getCategories());

        foreach ($responses as $response) {
            $this->assertEquals(202, $response->getStatusCode());
        }
    }

    /**
     * @depends testCreateCategoryDirect
     * @depends testGetCategories
     *
     * @throws Exception
     */
    public function testDeleteCategoryEventInSpecificCatalogCode()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, [
            'requestType' => 'direct',
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);
        $responses = [];

        // Act
        foreach ($this->getCategoryCodes($sampleCategories) as $categoryCode) {
            $responses[] = $this->sdk->getCatalogService()->deleteCategory(
                $categoryCode,
                [
                    'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
                ]
            );
        }

        usleep(self::SLEEP_TIME_AFTER_EVENT * 5);

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories), [
            'catalogCode' => self::SAMPLE_CATALOG_CODE_NON_DEFAULT
        ]);
        /** @noinspection PhpParamsInspection */
        $this->assertCount(0, $categories->getCategories());

        foreach ($responses as $response) {
            $this->assertEquals(202, $response->getStatusCode());
        }
    }

    /**
     * @param array  $categoryData
     * @param string $expectedException
     *
     * @throws Exception
     *
     * @dataProvider provideCreateCategoryWithMissingRequiredFields
     */
    public function testCreateCategoryEventWithMissingRequiredFields(array $categoryData, $expectedException)
    {
        $this->markTestSkipped(
            'Currently there is no validation for events! Waiting for the implementation in service'
        );

        // Arrange
        $category = new Category\Create($categoryData);

        // Assert
        $this->expectException(get_class($expectedException));

        // Act
        $this->createCategories([$category]);
    }

    /**
     * @param array  $categoryData
     * @param string $expectedException
     *
     * @throws Exception
     *
     * @dataProvider provideCreateCategoryWithInvalidDataTypes
     */
    public function testCreateCategoryEventInvalidDataTypes($categoryData, $expectedException)
    {
        $this->markTestSkipped(
            'Currently there is no validation for events! Waiting for the implementation in service'
        );

        // Arrange
        $category = new Category\Create($categoryData);

        // Assert
        $this->expectException(get_class($expectedException));

        // Act
        $this->createCategories([$category]);
    }
}
