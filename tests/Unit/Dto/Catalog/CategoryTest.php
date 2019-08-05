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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Category;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Dto\Catalog\Category
 */
class CategoryTest extends TestCase
{
    public function testAddingLocalesOnGet()
    {
        // Arrange
        $name = new Category\Dto\Name();
        $name->add('en-us', 'Name EN');

        $description = new Category\Dto\Description();
        $description->add('en-us', 'Description EN');

        $url = new Category\Dto\Url();
        $url->add('en-us', 'http://google.com');

        $image = new Category\Dto\Image();
        $image->add('en-us', 'http://image.com');

        $category = new Category\Create();
        $category->setCode('test-category')
            ->setSequenceId(1)
            ->setName($name)
            ->setDescription($description)
            ->setUrl($url)
            ->setImage($image)
            ->setExternalUpdateDate('2019-12-15T00:00:00.000Z')
            ->setStatus(Category\Create::STATUS_ACTIVE);

        // Act
        $categoryName = $category->getName();
        $categoryName->add('de-de', 'Name DE');

        $categoryDescription = $category->getDescription();
        $categoryDescription->add('de-de', 'Description DE');

        $categoryUrl = $category->getUrl();
        $categoryUrl->add('de-de', 'http://google.de');

        $categoryImage = $category->getImage();
        $categoryImage->add('de-de', 'http://image.de');

        // Assert
        $this->assertCount(2, $category->getName());
        $this->assertTrue(isset($category->getName()['de-de']));
        $this->assertTrue(isset($category->getName()['en-us']));

        $this->assertCount(2, $category->getDescription());
        $this->assertTrue(isset($category->getDescription()['de-de']));
        $this->assertTrue(isset($category->getDescription()['en-us']));

        $this->assertCount(2, $category->getUrl());
        $this->assertTrue(isset($category->getUrl()['de-de']));
        $this->assertTrue(isset($category->getUrl()['en-us']));

        $this->assertCount(2, $category->getImage());
        $this->assertTrue(isset($category->getImage()['de-de']));
        $this->assertTrue(isset($category->getImage()['en-us']));
    }
}
