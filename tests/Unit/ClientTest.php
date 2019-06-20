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

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use kamermans\OAuth2\OAuth2Middleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\Http\ClientInterface;

class ClientTest extends TestCase
{
    /** @var Client */
    private $subjectUnderTest;

    /** @var GuzzleClientInterface|MockObject */
    private $guzzleClient;

    /** @var OAuth2Middleware */
    private $oAuthMiddleware;

    public function setUp()
    {
        $this->guzzleClient = $this
            ->getMockBuilder(GuzzleClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->oAuthMiddleware = $this
            ->getMockBuilder(OAuth2Middleware::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subjectUnderTest = new Client(
            $this->guzzleClient,
            $this->oAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );
    }

    public function testCreateInstanceShouldReturnAClient()
    {
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(ClientInterface::class, Client::createInstance(
            'test',
            'secret',
            'TM2'
        ));
    }

    /**
     * @param string $expectedUrl
     * @param string $serviceName
     * @param string $path
     *
     * @dataProvider provideBuildServiceUrlFixtures
     */
    public function testBuildServiceUrl($expectedUrl, $serviceName, $path)
    {
        $this->assertEquals($expectedUrl, $this->subjectUnderTest->buildServiceUrl($serviceName, $path));
    }

    public function provideBuildServiceUrlFixtures()
    {
        return [
            'should replace {service} with service name'       => [
                'expectedUrl' => 'http://catalog.local/v1/merchants/TM2/',
                'serviceName' => 'catalog',
                'path'        => ''
            ],
            'should left-trim slashes from path and append it' => [
                'expectedUrl' => 'http://catalog.local/v1/merchants/TM2/products/prod1',
                'serviceName' => 'catalog',
                'path'        => '///products/prod1'
            ]
        ];
    }

    public function doRequestFixtures()
    {
        return [
            'should call requested service directly for GET calls' => [
                'expectedUrl' => '',
                'service'     => 'catalog',
                'env'         => ''
            ]
        ];
    }
}
