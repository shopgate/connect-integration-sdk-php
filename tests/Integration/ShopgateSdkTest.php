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

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\ShopgateSdk;

abstract class ShopgateSdkTest extends TestCase
{
    const SLEEP_TIME_AFTER_EVENT = 2;
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
                'env'
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
        foreach ($deleteMethods as $deleteMethod) {
            $this->services[$serviceKey][$deleteMethod] = [];
        }
    }

    /**
     * @param string   $service
     * @param string   $deleteMethod
     * @param string[] $entityIds
     */
    protected function deleteEntitiesAfterTestRun($service, $deleteMethod, $entityIds)
    {
        $this->services[$service][$deleteMethod] = array_merge($this->services[$service][$deleteMethod], $entityIds);
    }

    /**
     * Runs before every test
     */
    public function setUp()
    {
        $this->sdkConfig = [
            'clientId'     => getenv('clientId'),
            'clientSecret' => getenv('clientSecret'),
            'merchantCode' => getenv('merchantCode'),
            'env'          => getenv('env'),
        ];

        if ($baseUri = getenv('baseUri')) {
            $this->sdkConfig['base_uri'] = $baseUri;
        }
        if ($oauthBaseUri = getenv('oauthBaseUri')) {
            $this->sdkConfig['oauth']['base_uri'] = $oauthBaseUri;
        }
        if ($oauthStorage = getenv('oauthStoragePath')) {
            $this->sdkConfig['oauth']['storage_path'] = $oauthStorage;
        }
        $this->sdk = new ShopgateSdk($this->sdkConfig);
    }

    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->services as $service) {
            foreach ($service as $deleteMethod => $entityIds) {
                foreach ($entityIds as $entityId) {
                    $service['service']->{$deleteMethod}($entityId, ['requestType' => 'direct', 'force' => true]);
                }
            }
        }
    }
}
