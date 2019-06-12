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
    const SLEEP_TIME_AFTER_EVENT = 1;
    /** @var array */
    protected $sdkConfig = [];
    /** @var ShopgateSdk */
    protected $sdk;

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
            $this->sdkConfig['oauth']['base_uri'] = $baseUri;
        }
        if ($oauthStorage = getenv('oauthStoragePath')) {
            $this->sdkConfig['oauth']['storage_path'] = $oauthStorage;
        }
        $this->sdk = new ShopgateSdk($this->sdkConfig);
    }
}
