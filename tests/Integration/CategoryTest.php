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
use Shopgate\ConnectSdk\DTO\Catalog\Product\Name;

class CategoryTest extends ShopgateSdkTest
{
    const CATEGORY_CODE = 'integration-test';

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
        $this->assertCount(2, $categories->getCategories());
    }

    public function testUpdateCategoryDirect()
    {
        // Arrange
        $newName = "Renamed Product (Direct)";
        $payload = new Category\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $this->sdk->catalog->updateCategory(self::CATEGORY_CODE, $payload, [
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
            $this->sdk->catalog->deleteCategory($categoryCode, [
                'requestType' => 'direct'
            ]);
        }

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        $this->assertCount(0, $categories->getCategories());
    }


    // TODO: It seems only one category is created in the service (Oliver is on it)
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
        $this->assertCount(2, $categories->getCategories());
    }

    public function testUpdateCategoryEvent()
    {
        // Arrange
        $newName = "Renamed Product (Event)";
        $payload = new Category\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $response = $this->sdk->catalog->updateCategory(self::CATEGORY_CODE, $payload);
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
            $responses[] = $this->sdk->catalog->deleteCategory($categoryCode);
        }
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $categories = $this->getCategories($this->getCategoryCodes($sampleCategories));
        $this->assertCount(0, $categories->getCategories());

        foreach ($responses as $response) {
            $this->assertEquals(202, $response->getStatusCode());
        }
    }

    /**
     * @param array $categoryCodes
     * @return Category\GetList
     */
    private function getCategories($categoryCodes = [])
    {
        return $this->sdk->catalog->getCategories(['filters' => ['code' => ['$in' => $categoryCodes]]]);
    }

    /**
     * @param Category\Create[] $sampleCategories
     * @param array $meta
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createCategories(array $sampleCategories, array $meta = [])
    {
        return $this->sdk->catalog->addCategories($sampleCategories, $meta);
    }

    /**
     * @return Category\Create[]
     */
    private function provideSampleCategories()
    {
        $payload = new Category\Create();
        $name = new Category\Name(['en-us' => 'Denim Jeans']);
        $payload->setCode(self::CATEGORY_CODE)->setName($name)->setSequenceId(1);

        $payload2 = (new Category\Create())
            ->setCode(self::CATEGORY_CODE . '_2')
            ->setName(new Category\Name(['en-us' => 'Denim Skirts']))
            ->setSequenceId(2);
        return [$payload, $payload2];
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
            $categoryCodes[] = $category->getCode();
        }

        return $categoryCodes;
    }
}
