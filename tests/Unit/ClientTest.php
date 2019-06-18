<?php

namespace Shopgate\ConnectSdk\Tests\Unit;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use kamermans\OAuth2\OAuth2Middleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Client;
use Shopgate\ConnectSdk\ClientInterface;

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
        $this->guzzleClient     = $this
            ->getMockBuilder(GuzzleClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->oAuthMiddleware  = $this
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

    public function doRequestFixtures()
    {
        return [
            'should call requested service directly for GET calls' => [
                'expectedUrl' => '',
                'service' => 'catalog',
                'env' => ''
            ]
        ];
    }
}
