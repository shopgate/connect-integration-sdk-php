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
use Shopgate\ConnectSdk\Dto\Catalog\Product\Create;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;
use Shopgate\ConnectSdk\Exception\Exception;

class CreateTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetPropertiesValueIsArray()
    {
        $entry = [
            'properties' => [
                [
                    'code' => 'property_code_1',
                    'name' => 'property 1 english',
                    'type' => 'product',
                    'value' => 'a name',
                    'displayGroup' => 'features',
                ],
                [
                    'code' => 'property_code_2',
                    'name' => 'property 2 english',
                    'type' => 'simple',
                    'value' => [
                        'attributeValueCode1',
                        'attributeValueCode2',
                        'attributeValueCode3'
                    ],
                    'displayGroup' => 'properties',
                ],
                [
                    'code' => 'property_code_3',
                    'name' => 'property 3 english',
                    'type' => 'simple',
                    'value' => [
                        'en-us' => 'Some name',
                        'de-de' => 'Ein name',
                    ],
                    'displayGroup' => 'properties',
                ],
            ]
        ];

        $get = new Create($entry);
        $properties = $get->getProperties();

        $this->assertCount(3, $properties);
        $this->assertTrue(is_array($properties));
        $this->assertInstanceOf(Dto\Properties::class, $properties[0]);
        $this->assertInstanceOf(Dto\Properties::class, $properties[1]);
        $this->assertInstanceOf(Dto\Properties::class, $properties[2]);

        $this->assertEquals('a name', $properties[0]->getValue());
        $this->assertEquals([
            'attributeValueCode1',
            'attributeValueCode2',
            'attributeValueCode3'
        ], $properties[1]->getValue());
        $this->assertEquals('Some name', $properties[2]->getValue()->{'en-us'});
        $this->assertEquals('Ein name', $properties[2]->getValue()->{'de-de'});
    }
}
