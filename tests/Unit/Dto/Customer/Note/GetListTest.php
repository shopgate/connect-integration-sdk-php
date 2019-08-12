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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Customer\Note;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Customer\Note\Get;
use Shopgate\ConnectSdk\Dto\Customer\Note\GetList;
use Shopgate\ConnectSdk\Dto\Meta;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Dto\Customer\Note\GetList
 */
class GetListTest extends TestCase
{
    /**
     * Testing recursive reference for notes
     */
    public function testBasicDtoList()
    {
        $entry = [
            'meta' => [
                'limit' => 1
            ],
            'notes' => [
                ['id' => 'someId'],
                ['externalCode' => 'someCode']
            ]
        ];
        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(1, $getList->getMeta()->getLimit());

        $notes = $getList->getNotes();
        $this->assertCount(2, $notes);
        $this->assertTrue(is_array($notes));
        $this->assertInstanceOf(Get::class, $notes[0]);
        $this->assertInstanceOf(Get::class, $notes[1]);
        $this->assertEquals('someId', $notes[0]->getId());
        $this->assertEquals('someCode', $notes[1]->getExternalCode());
    }
}
