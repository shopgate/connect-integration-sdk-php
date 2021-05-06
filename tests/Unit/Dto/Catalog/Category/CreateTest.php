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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Category;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Category;

class CreateTest extends TestCase
{
    /**
     * @throws \Shopgate\ConnectSdk\Exception\InvalidDataTypeException
     */
    public function testCreateCategoryJSON()
    {
        $create = (new Category\Create())
            ->setName(
                (new Category\Dto\Name())
                    ->add('de-de', (string)'Category 1')
            )->setDescription(
                (new Category\Dto\Description())
                    ->add('de-de', (string)'Lorem ipsum dolor sit amet')
            )->setUrl(
                (new Category\Dto\Url())
                    ->add('de-de', '/category1')
            )
            ->setImage(
                (new Category\Dto\Image())
                    ->add('de-de', 'https://picsum.photos/200')
            )
            ->setCode('cat1')
            ->setParentCategoryCode(null)
            ->setSequenceId(1)
            ->setStatus(Category::STATUS_ACTIVE);

        $expected = [
            'name' => (object)[
                'de-de' => 'Category 1',
            ],
            'description' => (object)[
                'de-de' => 'Lorem ipsum dolor sit amet',
            ],
            'url' => (object)[
                'de-de' => '/category1',
            ],
            'image' => (object)[
                'de-de' => 'https://picsum.photos/200',
            ],
            'code' => 'cat1',
            'parentCategoryCode' => null,
            'sequenceId' => 1,
            'status' => 'active',
        ];
        /**
         * Should have nullable parent code
         */
        $this->assertArraySubset($expected, $create->toArray());
        $this->assertEquals(json_encode($expected), $create->toJson());

        /**
         * Should force parent code to null
         */
        $create->setParentCategoryCode('');
        $this->assertArraySubset($expected, $create->toArray());
        $this->assertEquals(json_encode($expected), $create->toJson());
    }
}
