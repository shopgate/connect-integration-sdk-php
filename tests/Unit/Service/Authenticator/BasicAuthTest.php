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

namespace Shopgate\CloudIntegrationSdk\Tests\Unit\Service\Authenticator;

use Shopgate\CloudIntegrationSdk\Service\Authenticator\BasicAuth;
use Shopgate\CloudIntegrationSdk\Service\Authenticator\Exception\Unauthorized;
use Shopgate\CloudIntegrationSdk\Tests\Stubs\Repository\ClientCredentials;
use Shopgate\CloudIntegrationSdk\ValueObject\Request\Request;

class BasicAuthTest extends \PHPUnit\Framework\TestCase
{
    /** @var BasicAuth */
    private $basicAuth;

    protected function setUp()
    {
        $this->basicAuth = new BasicAuth(new ClientCredentials());
    }

    /**
     * @param \Exception $expectedResult
     * @param string     $requestAuthorizationHeader
     *
     * @covers       \Shopgate\CloudIntegrationSdk\Service\Authenticator\BasicAuth::authenticate()
     * @dataProvider provideAuthenticateCases
     */
    public function testAuthenticate($expectedResult, $requestAuthorizationHeader)
    {
        /** @var Request | \PHPUnit\Framework\MockObject\MockObject $request */
        $request = $this
            ->getMockBuilder(Request::class)
            ->setConstructorArgs(['uri', 'method'])
            ->setMethods(['getHeader'])
            ->getMock();
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn($requestAuthorizationHeader);

        $error = 0;
        try {
            $this->basicAuth->authenticate($request);
        } catch (Unauthorized $exception) {
            $error = 1;
        } catch (\Exception $exception) {
            $error = 2;
        }

        $this->assertEquals($expectedResult, $error);
    }

    /**
     * @return array
     */
    public function provideAuthenticateCases()
    {
        return [
            'correct authorization header'   => [
                0,
                'Basic ' . base64_encode('someClientId:someClientSecret'),
            ],
            'incorrect authorization header' => [
                1,
                'Basic ' . base64_encode('Basic someDifferentClientId:someDifferentClientSecret'),
            ],
            'missing authorization header'   => [
                1,
                '',
            ],
        ];
    }
}
