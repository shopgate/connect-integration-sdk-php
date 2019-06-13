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

namespace Shopgate\ConnectSdk\Tests\Integration;

use Shopgate\ConnectSdk\DTO\Catalog\Category;
use Shopgate\ConnectSdk\DTO\Catalog\Product\Dto\Name;

class CategoryTest extends ShopgateSdkTest
{
    const CATEGORY_CODE = 'integration-test';

    private $cleanUpCategoryCodes;

    /**
     * Runs before every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanUpCategoryCodes = [];
    }

    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->cleanUpCategoryCodes as $categoryCode) {
            $this->sdk->getCatalogService()->deleteCategory($categoryCode);
        }
    }

    /**
     * Direct requests
     */

    /**
     * valid
     */

    public function testCreateCategoryDirect()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();

        // Act
        $this->createCategories($sampleCategories, [
            'requestType' => 'direct'
        ]);

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    public function testUpdateCategoryDirect()
    {
        // Arrange
        $newName = 'Renamed Product (Direct)';
        $category = new Category\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $this->sdk->getCatalogService()->updateCategory(self::CATEGORY_CODE, $category, [
            'requestType' => 'direct'
        ]);

        // Assert
        $categories = $this->getCategories([self::CATEGORY_CODE]);
        $updatedCategory = $categories->getCategories()[0];
        $this->assertEquals($newName, $updatedCategory->getName());
    }

    public function testDeleteCategoryDirect()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();

        // Act
        foreach ($this->getCategoryCodes($sampleCategories) as $categoryCode) {
            $this->sdk->getCatalogService()->deleteCategory($categoryCode, [
                'requestType' => 'direct'
            ]);
        }

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        /** @noinspection PhpParamsInspection */
        $this->assertCount(0, $categories->getCategories());
    }

    /**
     * error cases
     */


    /**
     * @param array $categoryData
     * @param string $expectedException
     *
     * @dataProvider provideCreateCategory_MissingRequiredFields
     */
    public function testCreateCategoryDirect_MissingRequiredFields(array $categoryData, $expectedException)
    {
        // Arrange
        $category = new Category\Create($categoryData);

        // Assert
        $this->expectException($expectedException);

        // Act
        $this->createCategories([$category], [
            'requestType' => 'direct'
        ]);
    }

    /**
     * @return array
     */
    public function provideCreateCategory_MissingRequiredFields()
    {
        return [
            'missing name' => [
                'categoryData' => [
                    'code' => 'category-test-code',
                    'sequenceId' => 1006
                ],
                'expectedException' => \Exception::class
            ],
            'missing code' => [
                'categoryData' => [
                    'name' => 'Test Category',
                    'sequenceId' => 1006
                ],
                'expectedException' => \Exception::class
            ],
            'missing sequenceId' => [
                'categoryData' => [
                    'name' => 'Test Category',
                    'code' => 'category-test-code',
                ],
                'expectedException' => \Exception::class
            ],
        ];
    }

    /**
     * @param array $categoryData
     * @param string $expectedException
     *
     * @dataProvider provideCreateCategory_InvalidDataTypes
     */
    public function testCreateCategoryDirect_InvalidDataTypes($categoryData, $expectedException)
    {
        // Arrange
        $category = new Category\Create($categoryData);

        // Assert
        $this->expectException($expectedException);

        // Act
        $this->createCategories([$category], [
            'requestType' => 'direct'
        ]);
    }

    /**
     * @return array
     */
    public function provideCreateCategory_InvalidDataTypes()
    {
        return [
            'wrong name data type' => [
                'categoryData' => [
                    'name' => 12345,
                    'code' => 'category-test-code',
                    'sequenceId' => 1006
                ],
                'expectedException' => \Exception::class
            ],
            'wrong code data type' => [
                'categoryData' => [
                    'name' => 'Test Category',
                    'code' => 123456,
                    'sequenceId' => 1006
                ],
                'expectedException' => \Exception::class
            ],
            'wrong sequenceId data type' => [
                'categoryData' => [
                    'name' => 'Test Category',
                    'code' => 'category-test-code',
                    'sequenceId' => '1006'
                ],
                'expectedException' => \Exception::class
            ],
        ];
    }

    public function testUpdateCategory_WithoutAnyDataGiven()
    {
        // Arrange
        $categoryCode = 'example-code';
        $existinCategory = $this->provideSampleCreateCategory(
            $categoryCode,
            'test category',
            'http://www.google.e/image.png',
            'http://www.google.de',
            'test description',
            '12345'
        );
        $this->sdk->getCatalogService()->addCategories([$existinCategory], [
            'requestType' => 'direct'
        ]);
        $updateCategory = new Category\Update();

        // Assert
        $this->expectException(\Exception::class);

        // Act
        $this->sdk->getCatalogService()->updateCategory($categoryCode, $updateCategory, [
            'requestType' => 'direct'
        ]);

        //TODO: clean up
    }

    public function testUpdateCategory_NonExistingCategory()
    {
        // Arrange
        $nonExistentCategoryCode = 'non-existent';
        $category = $this->provideSampleUpdateCategory('test non existent category');

        // Assert
        $this->expectException(\Exception::class);

        // Act
        $this->sdk->getCatalogService()->updateCategory($nonExistentCategoryCode, $category, [
            'requestType' => 'direct'
        ]);
    }

    /**
     * Events
     */

    // TODO: It seems only one category is created in the service. Cause of this bug:
    // https://gitlab.localdev.cc/omnichannel/services/worker/blob/v1.0.0-beta.10c/app/EventController.js#L37
    // the return will interrupt the execution of following events
    // will be fixed once we can use something later than omni-worker: v1.0.0-beta.10c
    public function testCreateCategoryEvent()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();

        // Act
        $response = $this->createCategories($sampleCategories);
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        $this->assertEquals(202, $response->getStatusCode());
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $categories->getCategories());
    }

    public function testUpdateCategoryEvent()
    {
        // Arrange
        $newName = 'Renamed Product (Event)';
        $payload = new Category\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $response = $this->sdk->getCatalogService()->updateCategory(self::CATEGORY_CODE, $payload);
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $categories = $this->getCategories([self::CATEGORY_CODE]);
        $updatedCategory = $categories->getCategories()[0];
        $this->assertEquals(202, $response->getStatusCode());
        $this->assertEquals($newName, $updatedCategory->getName());
    }

    public function testDeleteCategoryEvent()
    {
        // Arrange
        $sampleCategories = $this->provideSampleCategories();
        $responses = [];

        // Act
        foreach ($this->getCategoryCodes($sampleCategories) as $categoryCode) {
            $responses[] = $this->sdk->getCatalogService()->deleteCategory($categoryCode);
        }
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        /** @noinspection PhpParamsInspection */
        $this->assertCount(0, $categories->getCategories());

        foreach ($responses as $response) {
            $this->assertEquals(202, $response->getStatusCode());
        }
    }

    /**
     * error cases
     */


    /**
     * @param array $categoryData
     * @param string $expectedException
     *
     * @dataProvider provideCreateCategory_MissingRequiredFields
     */
    public function testCreateCategoryEvent_MissingRequiredFields(array $categoryData, $expectedException)
    {
        // Arrange
        $category = new Category\Create($categoryData);

        // Assert
        $this->expectException($expectedException);

        // Act
        $this->createCategories([$category]);
    }

    /**
     * @param array $categoryData
     * @param string $expectedException
     *
     * @dataProvider provideCreateCategory_InvalidDataTypes
     */
    public function testCreateCategoryEvent_InvalidDataTypes($categoryData, $expectedException)
    {
        // Arrange
        $category = new Category\Create($categoryData);

        // Assert
        $this->expectException($expectedException);

        // Act
        $this->createCategories([$category]);
    }




    /**
     * @param array $categoryCodes
     * @return Category\GetList
     */
    private function getCategories($categoryCodes = [])
    {
        return $this->sdk->getCatalogService()->getCategories(['filters' => ['code' => ['$in' => $categoryCodes]]]);
    }

    /**
     * @param Category\Create[] $sampleCategories
     * @param array $meta
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createCategories(array $sampleCategories, array $meta = [])
    {
        return $this->sdk->getCatalogService()->addCategories($sampleCategories, $meta);
    }

    /**
     * @return Category\Create[]
     */
    private function provideSampleCategories()
    {
        $payload = new Category\Create();
        $name = new Category\Dto\Name(['en-us' => 'Denim Jeans']);
        $payload->setCode(self::CATEGORY_CODE)->setName($name)->setSequenceId(1);

        $payload2 = (new Category\Create())
            ->setCode(self::CATEGORY_CODE . '_2')
            ->setName(new Category\Dto\Name(['en-us' => 'Denim Skirts']))
            ->setSequenceId(2);
        return [$payload, $payload2];
    }

    /**
     * @param string $name
     * @param string $image
     * @param string $url
     * @param string $description
     * @param string $parentCategoryCode
     * @return Category\Update
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
     * @param string $code
     * @param string $name
     * @param int $sequenceId
     * @param string $image
     * @param string $url
     * @param string $description
     * @param string $parentCategoryCode
     * @return Category\Create
     */
    private function provideSampleCreateCategory(
        $code,
        $name,
        $sequenceId,
        $image = null,
        $url = null,
        $description = null,
        $parentCategoryCode = null

    ) {
        $category = new Category\Create();
        $category->setCode($code)
            ->setName(new Category\Dto\Name(['en-us' => $name]))
            ->setSequenceId($sequenceId);
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
     * @param Category\Create[] $categories
     *
     * @return string[]
     */
    private function getCategoryCodes($categories)
    {

        $categoryCodes = [];
        foreach ($categories as $category) {
            $categoryCodes[] = $category->code;
        }

        return $categoryCodes;
    }
}
