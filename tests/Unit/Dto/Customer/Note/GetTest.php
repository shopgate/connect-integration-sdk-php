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

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Dto\Customer\Note\Get
 */
class GetTest extends TestCase
{
    /**
     * Tests minimal DTO structure return
     */
    public function testBasicProperties()
    {
        $entry = [
            'id'           => 'someId',
            'externalCode' => 'someCode',
            'note'         => 'someNote',
            'date'         => 'someDate',
            'creator'      => 'someCreator',
        ];
        $get   = new Get($entry);

        $this->assertEquals('someId', $get->getId());
        $this->assertEquals('someCode', $get->getExternalCode());
        $this->assertEquals('someNote', $get->getNote());
        $this->assertEquals('someDate', $get->getDate());
        $this->assertEquals('someCreator', $get->getCreator());
    }
}
