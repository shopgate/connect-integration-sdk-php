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

namespace Shopgate\ConnectSdk\Tests\Integration;

use Exception;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\ShopgateSdk;

abstract class ShopgateSdkTest extends TestCase
{
    const SLEEP_TIME_AFTER_EVENT = 300000;

    /** @var array */
    protected $sdkConfig = [];

    /** @var ShopgateSdk */
    protected $sdk;

    /** @var ['$service' => ['$delete_method' => [string, string...]]] */
    protected $services = [];

    /**
     * Main setup before any tests are ran, runs once
     */
    public static function setUpBeforeClass()
    {
        $env = Dotenv::create(__DIR__);
        $env->load();
        $env->required(
            [
                'clientId',
                'clientSecret',
                'merchantCode',
            ]
        );
    }

    /**
     * @param string   $serviceKey
     * @param          $service
     * @param string[] $deleteMethods
     */
    protected function registerForCleanUp($serviceKey, $service, $deleteMethods)
    {
        $this->services[$serviceKey]['service'] = $service;
        foreach ($deleteMethods as $deleteMethod => $parameters) {
            $this->services[$serviceKey][$deleteMethod] = [];
            $this->services[$serviceKey][$deleteMethod]['ids'] = [];
            $this->services[$serviceKey][$deleteMethod]['parameters'] = $parameters;
        }
    }

    /**
     * @param string   $service
     * @param string   $deleteMethod
     * @param string[] $entityIds
     */
    protected function deleteEntitiesAfterTestRun($service, $deleteMethod, $entityIds)
    {
        $this->services[$service][$deleteMethod]['ids'] = array_merge($this->services[$service][$deleteMethod]['ids'], $entityIds);
    }

    /**
     * Runs before every test
     *
     * @throws Exception
     */
    public function setUp()
    {
        $client = Client::createInstance(
            getenv('clientId'),
            getenv('clientSecret'),
            getenv('merchantCode'),
            getenv('username'),
            getenv('username'),
            getenv('baseUri')
                ?: '',
            getenv('env')
                ?: '',
            getenv('accessTokenPath')
                ?: ''
        );

        if ((int)getenv('requestLogging')) {
            $client->enableRequestLogging(
                new Logger('request_logger_integration_tests', [new StreamHandler('php://stdout')])
            );
        }

        $this->sdk = new ShopgateSdk(['client' => $client]);
    }

    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->services as $service) {
            foreach ($service as $deleteMethod => $entityIds) {
                if (!is_array($entityIds)) {
                    continue;
                }
                foreach ($entityIds['ids'] as $entity) {
                    $parameters = is_array($entity) ? $entity : [$entity];
                    $parameters[] = array_merge(
                        ['requestType' => 'direct'],
                        $entityIds['parameters']
                    );

                    call_user_func_array([$service['service'], $deleteMethod], $parameters);
                }
            }
        }
    }
}
