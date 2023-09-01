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

namespace Shopgate\ConnectSdk\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use \PHPUnit_Framework_MockObject_MockObject;
use Shopgate\ConnectSdk\Exception\MissingConfigFieldException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Service\BulkImport;
use Shopgate\ConnectSdk\Service\Catalog;
use Shopgate\ConnectSdk\ShopgateSdk;

class ShopgateSdkTest extends TestCase
{
    /** @var ClientInterface|PHPUnit_Framework_MockObject_MockObject|MockObject */
    private $client;

    /**
     * Set up needed objects
     */
    public function set_up()
    {
        $this->client = $this->getMockBuilder(ClientInterface::class)->getMock();
    }

    /**
     * @throws MissingConfigFieldException
     */
    public function testShouldConstructWithGivenClient()
    {
        $subjectUnderTest = new ShopgateSdk(['client' => $this->client]);

        $this->assertSame($this->client, $subjectUnderTest->getClient());
    }

    /**
     * @throws MissingConfigFieldException
     */
    public function testShouldConstructWithNewInstanceOfClient()
    {
        $subjectUnderTest = new ShopgateSdk(['clientId'     => 'test',
                                             'clientSecret' => 'secret',
                                             'merchantCode' => 'TM2',
                                             'username'     => 'username',
                                             'password'     => 'password',
                                             'env'          => 'dev',
        ]);

        $this->assertInstanceOf(ClientInterface::class, $subjectUnderTest->getClient());
    }

    /**
     * @throws MissingConfigFieldException
     */
    public function testShouldConstructWithGivenServices()
    {
        $catalog          = $this->getMockBuilder(Catalog::class)->disableOriginalConstructor()->getMock();
        $bulkImport       = $this->getMockBuilder(BulkImport::class)->disableOriginalConstructor()->getMock();
        $subjectUnderTest = new ShopgateSdk([
            'client'   => $this->client,
            'services' => [
                'catalog'    => $catalog,
                'bulkImport' => $bulkImport
            ]
        ]);

        $this->assertSame($catalog, $subjectUnderTest->getCatalogService());
        $this->assertSame($bulkImport, $subjectUnderTest->getBulkImportService());
    }

    /**
     * @throws MissingConfigFieldException
     */
    public function testShouldConstructWithNewInstancesOfServices()
    {
        $subjectUnderTest = new ShopgateSdk(['client' => $this->client]);

        $this->assertInstanceOf(Catalog::class, $subjectUnderTest->getCatalogService());
        $this->assertInstanceOf(BulkImport::class, $subjectUnderTest->getBulkImportService());
    }

    /**
     * @throws MissingConfigFieldException
     */
    public function testShouldConstructWithNewInstancesOfServicesNotPassed()
    {
        $catalog          = $this->getMockBuilder(Catalog::class)->disableOriginalConstructor()->getMock();
        $subjectUnderTest = new ShopgateSdk([
            'client'   => $this->client,
            'services' => [                'catalog'    => $catalog            ]
        ]);

        $this->assertSame($catalog, $subjectUnderTest->getCatalogService());
        $this->assertInstanceOf(BulkImport::class, $subjectUnderTest->getBulkImportService());
    }
}
