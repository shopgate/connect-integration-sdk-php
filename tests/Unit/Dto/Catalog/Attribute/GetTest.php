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
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Get;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Exception\Exception;

class GetTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     *
     * @throws Exception
     */
    public function testCategoryDto()
    {
        $entry = [
            'values' => [
                [
                    'code'       => 'black',
                    'sequenceId' => 0,
                    'name'       => 'Black',
                    'swatch'     => [
                        'type'  => AttributeValue::SWATCH_TYPE_IMAGE,
                        'value' => 'https://some.url/image.jpg',
                    ],
                ],
            ],
        ];

        $get = new Get($entry);
        $attributeValue = $get->getValues()[0];
        $this->assertCount(1, $get->getValues());
        $this->assertInstanceOf(AttributeValue\Get::class, $attributeValue);
        $this->assertInstanceOf(AttributeValue\Dto\Swatch::class, $attributeValue->getSwatch());
        $this->assertEquals(AttributeValue::SWATCH_TYPE_IMAGE, $attributeValue->getSwatch()->getType());
        $this->assertEquals('https://some.url/image.jpg', $attributeValue->getSwatch()->getValue());
    }
}
