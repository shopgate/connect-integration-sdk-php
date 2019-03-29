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

namespace Unit\Service\Authenticator;

use \Shopgate\CloudIntegrationSdk\Service\Authenticator\BasicAuth;
use \Shopgate\CloudIntegrationSdk\Service\Authenticator\Exception\Unauthorized;
use \Stubs\Repository\ClientCredentials;

class BasicAuthTest extends \PHPUnit\Framework\TestCase
{
    /** @var BasicAuth */
    private $basicAuth;

    public function setUp()
    {
        $this->basicAuth = new BasicAuth(new ClientCredentials());
    }

    /**
     * @param \Exception $expectedResult
     * @param string     $requestAuthorizationHeader
     *
     * @covers       BasicAuth::authenticate()
     * @dataProvider provideAuthenticateCases
     */
    public function test_authenticate($expectedResult, $requestAuthorizationHeader)
    {
        /** @var \Shopgate\CloudIntegrationSdk\ValueObject\Request\Request $request */
        $request = $this
            ->getMockBuilder('\Shopgate\CloudIntegrationSdk\ValueObject\Request\Request')
            ->setConstructorArgs(array('uri', 'method'))
            ->setMethods(array('getHeader'))
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
        return array(
            'correct authorization header'   => array(
                0,
                'Basic ' . base64_encode('someClientId:someClientSecret'),
            ),
            'incorrect authorization header' => array(
                1,
                'Basic ' . base64_encode('Basic someDifferentClientId:someDifferentClientSecret'),
            ),
            'missing authorization header'   => array(
                1,
                '',
            ),
        );
    }
}
