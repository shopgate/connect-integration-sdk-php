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

use GuzzleHttp as Guzzle;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Shopgate\ConnectSdk\Http\GuzzleClient;

class GuzzleHttpTest extends TestCase
{
    /**
     * @param array $config
     * @param array $expectedAuthHeader
     *
     * @dataProvider provideAuthenticationHeader
     * @throws ReflectionException
     * @todo-sg      : need to rework this test as this is no longer relevant
     */
    public function testGetAuthenticationHeader($config, $expectedAuthHeader)
    {
        $reflectionClass = new ReflectionClass(GuzzleClient::class);
        $method          = $reflectionClass->getMethod('getAuthenticationHeader');
        $method->setAccessible(true);

        $client     = new GuzzleClient($config);
        $authHeader = $method->invokeArgs($client, [[]]);

        $this->assertEquals($expectedAuthHeader, $authHeader);
    }

    /**
     * @return array
     */
    public function provideAuthenticationHeader()
    {
        return [
            'basic authentication'     => [
                ['auth' => ['user' => 'username', 'pass' => 'password']],
                [Guzzle\RequestOptions::AUTH => ['username', 'password']],
            ],
            'NO authentication'        => [
                '',
                [],
                [],
            ],
            'missing auth header data' => [
                ['auth' => ['user' => 'username']],
                [],
            ],
            'unknown authentication'   => [
                ['auth' => ['user' => 'username', 'pass' => 'password', 'digest' => 'dig']],
                [],
            ],
        ];
    }
}
