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
use Shopgate\ConnectSdk\Dto\Catalog\Product;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Dto\Catalog\Product
 */
class ProductTest extends TestCase
{
    public function testAddingLocalesOnGet()
    {
        // Arrange
        $image = new Product\Dto\MediaList\Media();
        $image->setCode('media_code_1')
            ->setType(Product\Dto\MediaList\Media::TYPE_IMAGE)
            ->setUrl('example.com/media1.jpg')
            ->setAltText('alt text 1')
            ->setSubTitle('Title Media 1')
            ->setSequenceId(0);

        $germanImage = new Product\Dto\MediaList\Media();
        $germanImage->setCode('media_code_2')
            ->setType(Product\Dto\MediaList\Media::TYPE_IMAGE)
            ->setUrl('example.com/media2.jpg')
            ->setAltText('alt text 2')
            ->setSubTitle('Title Media 2')
            ->setSequenceId(0);

        $media = new Product\Dto\MediaList();
        $media->add('en-us', [$image]);

        $product = new Product\Create();
        $product->setName(new Product\Dto\Name(['en-us' => 'Product Name']))
            ->setCode('unit-1')
            ->setCatalogCode('PNW Retail')
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setStatus(Product\Create::STATUS_ACTIVE)
            ->setIsInventoryManaged(true)
            ->setLongName(new Product\Dto\LongName(['en-us' => 'Product Long Name']))
            ->setShortDescription(new Product\Dto\ShortDescription(['en-us' => 'short description']))
            ->setLongDescription(new Product\Dto\LongDescription(['en-us' => 'long description']))
            ->setMedia($media);

        // Act
        $productName = $product->getName();
        $productName->add('de-de', 'Product Name DE');

        $productLongName = $product->getLongName();
        $productLongName->add('de-de', 'Product Long Name DE');

        $productShortDescription = $product->getShortDescription();
        $productShortDescription->add('de-de', 'Description Short DE');

        $productLongDescription = $product->getLongDescription();
        $productLongDescription->add('de-de', 'Description Long DE');

        $productMedia = $product->getMedia();
        $productMedia->add('de-de', [$germanImage]);

        // Assert
        $this->assertCount(2, $product->getName());
        $this->assertInstanceOf(Product\Dto\Name::class, $product->getName());
        $this->assertTrue(isset($product->getName()['de-de']));
        $this->assertTrue(isset($product->getName()['en-us']));

        $this->assertCount(2, $product->getLongName());
        $this->assertInstanceOf(Product\Dto\LongName::class, $product->getLongName());
        $this->assertTrue(isset($product->getLongName()['de-de']));
        $this->assertTrue(isset($product->getLongName()['en-us']));

        $this->assertCount(2, $product->getShortDescription());
        $this->assertInstanceOf(Product\Dto\ShortDescription::class, $product->getShortDescription());
        $this->assertTrue(isset($product->getShortDescription()['de-de']));
        $this->assertTrue(isset($product->getShortDescription()['en-us']));

        $this->assertCount(2, $product->getLongDescription());
        $this->assertInstanceOf(Product\Dto\LongDescription::class, $product->getLongDescription());
        $this->assertTrue(isset($product->getLongDescription()['de-de']));
        $this->assertTrue(isset($product->getLongDescription()['en-us']));

        $this->assertCount(2, $product->getMedia());
        $this->assertInstanceOf(Product\Dto\MediaList::class, $product->getMedia());
        $this->assertTrue(isset($product->getMedia()['de-de']));
        $this->assertTrue(isset($product->getMedia()['en-us']));
    }
}
