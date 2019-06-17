<?php

namespace Shopgate\ConnectSdk\Tests\Unit;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Client;

class ClientTest extends TestCase
{
    /** @var Client */
    private $subjectUnderTest;

    /** @var GuzzleClientInterface|MockObject */
    private $guzzleClient;

    public function setUp()
    {
        $this->guzzleClient     = $this->getMockBuilder(GuzzleClientInterface::class)->getMock();
        // $this->subjectUnderTest = new Client($this->guzzleClient);
    }

    public function testDoRequest()
    {
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
