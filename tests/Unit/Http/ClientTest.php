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

namespace Shopgate\ConnectSdk\Tests\Unit\Http;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use kamermans\OAuth2\OAuth2Middleware;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Update;
use Shopgate\ConnectSdk\Exception\Exception as ShopgateSdkException;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

class ClientTest extends TestCase
{
    /** @var Client */
    private $subjectUnderTest;

    /** @var GuzzleClientInterface|PHPUnit_Framework_MockObject_MockObject */
    private $client;

    /** @var HandlerStack */
    private $handlerStack;

    /** @var MockHandler */
    private $mockHandler;

    /** @var OAuth2Middleware */
    private $OAuthMiddleware;

    /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $logger;

    public function setUp()
    {
        $this->mockHandler = new MockHandler([]);
        $this->handlerStack = HandlerStack::create($this->mockHandler);

        $this->client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $this->handlerStack]])
            ->setMethods(null)
            ->getMock();

        $this->logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->getMock();

        /** @var OAuth2Middleware $OAuthMiddleware */
        $this->OAuthMiddleware = $this->getMockBuilder(OAuth2Middleware::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subjectUnderTest = new Client(
            $this->client,
            $this->OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );
    }

    /**
     * Test case where it should return the default client
     */
    public function testCreateInstanceShouldReturnClient()
    {
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf(ClientInterface::class, Client::createInstance(
            'test',
            'secret',
            'TM2',
            'username',
            'password'
        ));
    }

    public function testGetClientShouldReturnInjectedClient()
    {
        $this->assertSame($this->client, $this->subjectUnderTest->getClient());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testEnableRequestLoggingShouldInjectTheLoggerAndTemplate()
    {
        /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $logger
            ->expects($this->once())
            ->method('log')
            ->with('info', 'log template');

        $this->mockHandler->append(new Response(200));

        $this->subjectUnderTest->enableRequestLogging($logger, 'log template');
        $this->client->request('GET', 'things');
    }

    /**
     * @doesNotPerformAssertions
     * @throws Exception
     */
    public function testEnableRequestLoggingShouldNotFailIfNothingWasPassed()
    {
        $subjectUnderTest = Client::createInstance('123', '123', '123', 'username', 'password');
        $subjectUnderTest->enableRequestLogging();
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

    /**
     * @return array
     */
    public function provideBuildServiceUrlFixtures()
    {
        return [
            'should replace {service} with service name' => [
                'expectedUrl' => 'http://catalog.local/v1/merchants/TM2/',
                'serviceName' => 'catalog',
                'path' => ''
            ],
            'should left-trim slashes from path and append it' => [
                'expectedUrl' => 'http://catalog.local/v1/merchants/TM2/products/prod1',
                'serviceName' => 'catalog',
                'path' => '///products/prod1'
            ]
        ];
    }

    /**
     * @throws ShopgateSdkException
     */
    public function testRequestShouldBeSentAsEvent()
    {
        // Arrange
        $this->client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $this->handlerStack]])
            ->setMethods(['request'])
            ->getMock();
        $this->client
            ->expects($this->once())
            ->method('request')->with(
                'post',
                'http://event-receiver.local/v1/merchants/TM2/events',
                [
                    'json' => '{"events":[{"event":"entityUpdated","entity":"category","payload":{}}]}',
                    'http_errors' => false,
                    'connect_timeout' => 5.0
                ]
            )->willReturn(new Response());

        $this->subjectUnderTest = new Client(
            $this->client,
            $this->OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        // Act
        $this->subjectUnderTest->doRequest([
            'action' => 'update',
            'method' => 'post',
            'entity' => 'category',
            'requestType' => ShopgateSdk::REQUEST_TYPE_EVENT
        ]);
    }

    /**
     * @throws ShopgateSdkException
     */
    public function testParameterCatalogCodeIsPassedAlongInThePayload()
    {
        // Arrange
        $this->client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $this->handlerStack]])
            ->setMethods(['request'])
            ->getMock();
        $this->client
            ->expects($this->once())
            ->method('request')->with(
                'post',
                'http://event-receiver.local/v1/merchants/TM2/events',
                [
                    'json' => '{"events":[{"event":"entityUpdated","entity":"category","payload":{"catalogCode":"ABCD"}}]}',
                    'http_errors' => false,
                    'connect_timeout' => 5.0
                ]
            )->willReturn(new Response());

        $this->subjectUnderTest = new Client(
            $this->client,
            $this->OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        // Act
        $this->subjectUnderTest->doRequest([
            'action' => 'update',
            'method' => 'post',
            'entity' => 'category',
            'json' => new Update(),
            'requestType' => ShopgateSdk::REQUEST_TYPE_EVENT,
            'query' => [
                'catalogCode' => 'ABCD',
            ]
        ]);
    }

    /**
     * @throws ShopgateSdkException
     */
    public function testRequestShouldBeSentAsDirectRequest()
    {
        // Arrange
        $this->client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $this->handlerStack]])
            ->setMethods(['request'])
            ->getMock();
        $this->client
            ->expects($this->once())
            ->method('request')->with(
                $this->equalTo('post'),
                $this->equalTo('http://catalog.local/v1/merchants/TM2/categories/'),
                $this->equalTo(['connect_timeout' => 5.0])
            )->willReturn(new Response());

        $this->subjectUnderTest = new Client(
            $this->client,
            $this->OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        // Act
        $this->subjectUnderTest->doRequest([
            'action' => 'update',
            'method' => 'post',
            'entity' => 'category',
            'service' => 'catalog',
            'path' => 'categories/',
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT
        ]);
    }

    /**
     * @throws ShopgateSdkException
     */
    public function testDoRequestShouldMakeDirectCallOnGet()
    {
        // Arrange
        $this->client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $this->handlerStack]])
            ->setMethods(['request'])
            ->getMock();

        $this->client
            ->expects($this->once())
            ->method('request')->with(
                $this->equalTo('get'),
                $this->equalTo('http://catalog.local/v1/merchants/TM2/categories'),
                $this->anything()
            )->willReturn(new Response());

        $this->subjectUnderTest = new Client(
            $this->client,
            $this->OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        // Act
        $this->subjectUnderTest->doRequest([
            'service' => 'catalog',
            'method' => 'get',
            'path' => 'categories'
        ]);
    }

    /**
     * @throws ShopgateSdkException
     */
    public function testRequestTypeParameterGetsRemoved()
    {
        // Arrange
        $this->client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $this->handlerStack]])
            ->setMethods(['request'])
            ->getMock();

        $this->client
            ->expects($this->once())
            ->method('request')->with(
                $this->equalTo('get'),
                $this->equalTo('http://catalog.local/v1/merchants/TM2/categories'),
                $this->equalTo([
                    'query' => [],
                    'connect_timeout' => 5.0
                ])
            )->willReturn(new Response());

        $this->subjectUnderTest = new Client(
            $this->client,
            $this->OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        // Act
        $this->subjectUnderTest->doRequest([
            'service' => 'catalog',
            'method' => 'get',
            'path' => 'categories',
            'query' => [
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            ]
        ]);
    }
}
