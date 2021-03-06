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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\AttributeValue;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

class UpdateTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     *
     * @throws Exception
     */
    public function testUpdateDto()
    {
        $entry = [
            'code' => 'black',
            'sequenceId' => 0,
            'name' => ['en-us' => 'Black'],
            'swatch' => [
                'type' => AttributeValue::SWATCH_TYPE_IMAGE,
                'value' => 'https://some.url/image.jpg',
            ],
        ];

        $update = new AttributeValue\Update($entry);
        $this->assertInstanceOf(AttributeValue\Dto\Name::class, $update->getName());
        $this->assertInstanceOf(AttributeValue\Dto\Swatch::class, $update->getSwatch());
        $this->assertEquals(AttributeValue::SWATCH_TYPE_IMAGE, $update->getSwatch()->getType());
        $this->assertEquals('https://some.url/image.jpg', $update->getSwatch()->getValue());
        $this->assertSame(0, $update->getSequenceId());

        $update->getName()->add('ru-ru', 'Чёрный');
        $this->assertCount(2, $update->getName()->toArray());
        $this->assertSame('Black', $update->getName()->get('en-us'));
        $this->assertSame('Чёрный', $update->getName()->get('ru-ru'));
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function testAlternativeUpdateCreation()
    {
        $name = new AttributeValue\Dto\Name();
        $name->add('en-us', 'Black');
        $swatch = (new AttributeValue\Dto\Swatch())
            ->setValue('https://some.url/image.jpg')
            ->setType(AttributeValue::SWATCH_TYPE_IMAGE);
        $update = new AttributeValue\Update();
        $update->setSequenceId(0)
            ->setCode('black')
            ->setName($name)
            ->setSwatch($swatch);
        $this->assertInstanceOf(AttributeValue\Dto\Name::class, $update->getName());
        $this->assertInstanceOf(AttributeValue\Dto\Swatch::class, $update->getSwatch());
        $this->assertEquals(AttributeValue::SWATCH_TYPE_IMAGE, $update->getSwatch()->getType());
        $this->assertEquals('https://some.url/image.jpg', $update->getSwatch()->getValue());
        $this->assertSame(0, $update->getSequenceId());

        $update->getName()->add('ru-ru', 'Чёрный');
        $this->assertCount(2, $update->getName()->toArray());
        $this->assertSame('Black', $update->getName()->get('en-us'));
        $this->assertSame('Чёрный', $update->getName()->get('ru-ru'));
    }
}
