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

namespace Shopgate\CloudIntegrationSdk\Tests\Unit\Client;

use GuzzleHttp as Guzzle;
use Shopgate\CloudIntegrationSdk\Client\GuzzleHTTP;

class GuzzleHTTPTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $authentication
     * @param array  $config
     * @param array  $expectedAuthHeader
     *
     * @covers       \Shopgate\CloudIntegrationSdk\Client\GuzzleHTTP::setAuthenticationHeader()
     * @dataProvider provideAuthenticationHeader
     */
    public function testSetAuthenticationHeader($authentication, $config, $expectedAuthHeader)
    {
        $reflectionClass = new \ReflectionClass(GuzzleHTTP::class);
        $method = $reflectionClass->getMethod('setAuthenticationHeader');
        $method->setAccessible(true);

        $client = new GuzzleHTTP($authentication, $config);

        $authHeader = $method->invokeArgs($client, [[]]);

        $this->assertEquals($expectedAuthHeader, $authHeader);
    }

    /**
     * @return array
     */
    public function provideAuthenticationHeader()
    {
        return [
            'basic authentication' => [
                'basic',
                ['auth' => ['user' => 'username', 'pass' => 'password']],
                [Guzzle\RequestOptions::AUTH => ['username', 'password']]
            ],
            'NO authentication' => [
                '',
                [],
                []
            ],
            'missing auth header data' => [
                'basic',
                ['auth' => ['user' => 'username']],
                []
            ],
            'unknown authentication' => [
                'digest',
                ['auth' => ['user' => 'username', 'pass' => 'password', 'digest' => 'dig']],
                []
            ]
        ];
    }
}
