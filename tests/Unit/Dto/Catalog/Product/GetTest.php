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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Product;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Get;

class GetTest extends TestCase
{
    /**
     * Tests minimal DTO structure return
     */
    public function testBasicProperties()
    {
        $entry = [
            'identifiers' => ['upc' => 'UPC123'],
            'price' => ['price' => 50.01]
        ];
        $get = new Get($entry);
        $ids = $get->getIdentifiers();
        $price = $get->getPrice();

        $this->assertInstanceOf(Dto\Identifiers::class, $ids);
        $this->assertEquals('UPC123', $ids->getUpc());

        $this->assertInstanceOf(Dto\Price::class, $price);
        $this->assertEquals(50.01, $price->getPrice());
    }

    /**
     * Tests price DTO structure return
     */
    public function testGetPrice()
    {
        $entry = [
            'price' => [
                'salePrice' => 5.51,
                'volumePricing' => [
                    ['minQty' => 1],
                    ['priceType' => Dto\Price\VolumePricing::PRICE_TYPE_FIXED]
                ],
                'mapPricing' => [
                    ['price' => 4.50],
                    ['startDate' => '2019-12-04']
                ]
            ]
        ];
        $get = new Get($entry);
        $price = $get->getPrice();
        $volume = $price->getVolumePricing();
        $map = $price->getMapPricing();

        $this->assertInstanceOf(Dto\Price::class, $price);
        $this->assertTrue(is_array($volume));
        $this->assertInstanceOf(Dto\Price\VolumePricing::class, $volume[0]);
        $this->assertInstanceOf(Dto\Price\VolumePricing::class, $volume[1]);
        $this->assertTrue(is_array($map));
        $this->assertInstanceOf(Dto\Price\MapPricing::class, $map[0]);
        $this->assertInstanceOf(Dto\Price\MapPricing::class, $map[1]);

        $this->assertEquals(5.51, $price->getSalePrice());
        $this->assertEquals(1, $volume[0]->getMinQty());
        $this->assertEquals(Dto\Price\VolumePricing::PRICE_TYPE_FIXED, $volume[1]->getPriceType());
        $this->assertEquals(4.50, $map[0]->getPrice());
        $this->assertEquals('2019-12-04', $map[1]->getStartDate());
    }

    /**
     * Test category DTO reference
     */
    public function testGetCategories()
    {
        $entry = [
            'categories' => [
                ['code' => 'la'],
                ['isPrimary' => true]
            ]
        ];
        $get = new Get($entry);
        $categories = $get->getCategories();

        $this->assertCount(2, $categories);
        $this->assertTrue(is_array($categories));
        $this->assertInstanceOf(Dto\Categories::class, $categories[0]);
        $this->assertInstanceOf(Dto\Categories::class, $categories[1]);

        $this->assertEquals('la', $categories[0]->getCode());
        $this->assertTrue($categories[1]->getIsPrimary());
    }

    /**
     * Test media DTO reference
     */
    public function testGetMedia()
    {
        $entry = [
            'media' => [
                ['code' => 'la24'],
                ['url' => 'http://test.url']
            ]
        ];
        $get = new Get($entry);
        $media = $get->getMedia();
        $this->assertCount(2, $media);
        $this->assertTrue(is_array($media));
        $this->assertInstanceOf(Dto\MediaList\Media::class, $media[0]);
        $this->assertInstanceOf(Dto\MediaList\Media::class, $media[1]);

        $this->assertEquals('la24', $media[0]->getCode());
        $this->assertEquals('http://test.url', $media[1]->getUrl());
    }

    /**
     * Test Properties DTO references
     */
    public function testGetProperties()
    {
        $entry = [
            'properties' => [
                ['value' => ['en-us' => 'test']],
                ['name' => ['en-us' => 'Some name']],
                ['subDisplayGroup' => ['en-us' => 'Some subgroup']]
            ]
        ];

        $get = new Get($entry);
        $properties = $get->getProperties();
        $value = $properties[0]->getValue();
        $name = $properties[1]->getName();
        $subGroup = $properties[2]->getSubDisplayGroup();

        $this->assertCount(3, $properties);
        $this->assertTrue(is_array($properties));
        $this->assertInstanceOf(Dto\Properties::class, $properties[0]);
        $this->assertInstanceOf(Dto\Properties::class, $properties[1]);
        $this->assertInstanceOf(Dto\Properties::class, $properties[2]);
        $this->assertInstanceOf(Dto\Properties\Value::class, $value);
        $this->assertInstanceOf(Dto\Properties\Name::class, $name);
        $this->assertInstanceOf(Dto\Properties\SubDisplayGroup::class, $subGroup);

        $this->assertEquals('test', $value->{'en-us'});
        $this->assertEquals('Some name', $name->{'en-us'});
        $this->assertEquals('Some subgroup', $subGroup->{'en-us'});
    }

    /**
     * Test inventories DTO references
     */
    public function testInventories()
    {
        $entry = [
            'inventories' => [
                ['sku' => 'SKU-123'],
                ['available' => 5]
            ]
        ];
        $get = new Get($entry);
        $inventories = $get->getInventories();

        $this->assertCount(2, $inventories);
        $this->assertTrue(is_array($inventories));
        $this->assertInstanceOf(Dto\Inventory::class, $inventories[0]);
        $this->assertInstanceOf(Dto\Inventory::class, $inventories[1]);

        $this->assertEquals('SKU-123', $inventories[0]->getSku());
        $this->assertEquals(5, $inventories[1]->getAvailable());
    }

    /**
     * Test options DTO references
     */
    public function testOptions()
    {
        $entry = [
            'options' => [
                ['code' => 'someCode'],
                ['values' => [['additionalPrice' => 5.5], ['code' => 'testCode']]]
            ]
        ];
        $get = new Get($entry);
        $options = $get->getOptions();
        $values = $options[1]->getValues();

        $this->assertCount(2, $options);
        $this->assertTrue(is_array($options));
        $this->assertInstanceOf(Dto\Options::class, $options[0]);
        $this->assertInstanceOf(Dto\Options::class, $options[1]);
        $this->assertEquals('someCode', $options[0]->getCode());

        $this->assertCount(2, $values);
        $this->assertTrue(is_array($values));
        $this->assertEquals(5.5, $values[0]->getAdditionalPrice());
        $this->assertEquals('testCode', $values[1]->getCode());
    }

    /**
     * Test extras DTO references
     */
    public function testExtras()
    {
        $entry = [
            'extras' => [
                ['code' => 'someCode2'],
                ['values' => [['additionalPrice' => 5.6], ['code' => 'testCode2']]]
            ]
        ];
        $get = new Get($entry);
        $extras = $get->getExtras();
        $extrasValues = $extras[1]->getValues();

        $this->assertCount(2, $extras);
        $this->assertTrue(is_array($extras));
        $this->assertInstanceOf(Dto\Extras::class, $extras[0]);
        $this->assertInstanceOf(Dto\Extras::class, $extras[1]);
        $this->assertEquals('someCode2', $extras[0]->getCode());

        $this->assertCount(2, $extrasValues);
        $this->assertTrue(is_array($extrasValues));
        $this->assertEquals(5.6, $extrasValues[0]->getAdditionalPrice());
        $this->assertEquals('testCode2', $extrasValues[1]->getCode());
    }
}
