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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Attribute;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Get;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\GetList;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\Exception;

class GetListTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     *
     * @throws Exception
     */
    public function testCategoryDto()
    {
        $entry = [
            'meta' => [
                'limit' => 2,
            ],
            'attributes' => [
                [
                    'code' => 'color',
                    'type' => Attribute::TYPE_TEXT,
                    'use' => Attribute::USE_OPTION,
                    'name' => 'Color',
                    'values' => [
                        [
                            'code' => 'black',
                            'sequenceId' => 0,
                            'name' => 'Black',
                            'swatch' => [
                                'type' => 'image',
                                'value' => 'https://some.url/image.jpg'
                            ]
                        ]
                    ]
                ],
                [
                    'code' => 'size'
                ]
            ],
        ];

        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(2, $getList->getMeta()->getLimit());

        $attributes = $getList->getAttributes();
        $this->assertCount(2, $attributes);
        $this->assertInternalType('array', $attributes);
        $this->assertInstanceOf(Get::class, $attributes[0]);
        $this->assertInstanceOf(Get::class, $attributes[1]);
        $this->assertEquals('color', $attributes[0]->getCode());
        $this->assertEquals(Attribute::TYPE_TEXT, $attributes[0]->getType());
        $this->assertEquals(Attribute::USE_OPTION, $attributes[0]->getUse());
        $this->assertEquals('Color', $attributes[0]->getName());
        $this->assertEquals('size', $attributes[1]->getCode());

        $attributeValue = $attributes[0]->getValues()[0];
        $this->assertCount(1, $attributes[0]->getValues());
        $this->assertInstanceOf(AttributeValue\Get::class, $attributeValue);
        $this->assertInstanceOf(AttributeValue\Dto\Swatch::class, $attributeValue->getSwatch());
        $this->assertEquals(AttributeValue::SWATCH_TYPE_IMAGE, $attributeValue->getSwatch()->getType());
        $this->assertEquals('https://some.url/image.jpg', $attributeValue->getSwatch()->getValue());
    }
}
