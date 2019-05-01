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

namespace Shopgate\ConnectSdk\Tests\Unit\Services\Events;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Http\GuzzleClient;
use Shopgate\ConnectSdk\Services\Events\Client;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Base;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Services\Events\Client
 */
class ClientTest extends TestCase
{
    /**
     * @var MockBuilder
     */
    protected $httpClient;

    /**
     * Set up needed objects
     */
    protected function setUp()
    {
        $this->httpClient = $this->getMockBuilder(GuzzleClient::class)->disableOriginalConstructor();
    }

    /**
     * Tests the magic getter for catalog
     *
     * @covers \Shopgate\ConnectSdk\Services\Events\Client
     * @covers \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Base::__call
     */
    public function testGetCatalog()
    {
        $subjectUnderTest = new Client([]);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(Catalog::class, $subjectUnderTest->catalog);
    }

    /**
     * Checking the basic routing, more complicated tests should be done per class
     *
     * @covers \Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category\Async
     * @covers \Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category\Direct
     */
    public function testGetCatalogActions()
    {
        $mock             = $this->httpClient->getMock();
        $subjectUnderTest = new Client(['http_client' => $mock]);
        /** @noinspection PhpParamsInspection */
        $mock->expects($this->exactly(2))->method('send');
        $subjectUnderTest->catalog->updateCategory(1, [], []);
        $subjectUnderTest->catalog->updateCategory(1, [], [Base::KEY_TYPE => Base::SYNC]);
    }
}
