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

namespace Shopgate\ConnectSdk\Tests\Unit\Services\Events\Http;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Shopgate\ConnectSdk\Http\GuzzleClient;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Base;

class GuzzleClientTest extends TestCase
{
    /**
     * @dataProvider getResolveTemplateProvider
     *
     * @param string $expected     - expected return of function
     * @param string $component    - component that may be a template string
     * @param array  $options      - custom template variables passed to the function
     * @param array  $clientConfig - custom template variables passed to the client
     *
     * @throws ReflectionException
     */
    public function testResolveTemplate($expected, $component, array $options = [], array $clientConfig = [])
    {
        $method = self::getMethod(GuzzleClient::class, 'resolveTemplate');
        $client = new GuzzleClient($clientConfig);
        $return = $method->invokeArgs($client, [$component, $options]);
        $this->assertEquals($expected, $return);
    }

    /**
     * @param string $expected
     * @param array  $meta
     *
     * @dataProvider getClearInternalMetaProvider
     * @throws ReflectionException
     */
    public function testClearInternalMeta($expected, $meta)
    {
        $method = self::getMethod(GuzzleClient::class, 'cleanInternalMeta');
        $client = new GuzzleClient(['clientId' => '', 'clientSecret' => '', 'oauth' => ['base_uri' => '']]);
        $return = $method->invokeArgs($client, [$meta]);
        $this->assertEquals($expected, $return);
    }

    /**
     * @return array
     */
    public function getClearInternalMetaProvider()
    {
        return [
            [[], ['service' => 'dev']],
            [['notFiltered' => true], [Base::KEY_TYPE => 'dev', 'notFiltered' => true]],
            [['1' => 1, '2' => 2], ['1' => 1, 'ver' => 1, '2' => 2, 'env' => 'dev']]
        ];
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected static function getMethod($class, $method)
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $class = new ReflectionClass($class);
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @return array
     */
    public function getResolveTemplateProvider()
    {
        return [
            [
                'dev.shopgate',
                '{service}.shopgate',
                [],
                ['service' => 'dev', 'clientId' => '', 'clientSecret' => '', 'oauth' => ['base_uri' => '']]
            ],
            [
                'dev.shopgate',
                '{service}.shopgate',
                ['service' => 'dev'],
                ['clientId' => '', 'clientSecret' => '', 'oauth' => ['base_uri' => '']]
            ],
            [
                'dev.shopgate/v1',
                '{service}.shopgate/v{ver}',
                ['service' => 'dev', 'ver' => 1],
                ['clientId' => '', 'clientSecret' => '']
            ],
            [
                'dev.shopgate/v1',
                '{service}.shopgate/v{ver}',
                [],
                ['service' => 'dev', 'ver' => 1, 'clientId' => '', 'clientSecret' => '', 'oauth' => ['base_uri' => '']]
            ],
            [
                'dev.shopgate',
                '%7Bservice%7D.shopgate',
                [],
                ['service' => 'dev', 'clientId' => '', 'clientSecret' => '']
            ],
            [
                'dev.shopgate',
                '%7Bservice%7D.shopgate',
                ['service' => 'dev'],
                ['clientId' => '', 'clientSecret' => '', 'oauth' => ['base_uri' => '']]
            ]
        ];
    }
}
