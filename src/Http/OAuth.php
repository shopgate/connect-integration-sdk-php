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

namespace Shopgate\ConnectSdk\Http;

use GuzzleHttp\Client;
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\OAuth2Middleware;
use kamermans\OAuth2\Persistence\FileTokenPersistence;

class OAuth
{
    /** @var array */
    protected $config;

    /**
     * @param array $config
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $config = [])
    {
        $config['client']     = isset($config['client']) ? $config['client'] : new Client($config);
        $config['storage']    = isset($config['storage'])
            ? $config['storage']
            : new FileTokenPersistence($config['storage_path']);
        $config['grant_type'] = isset($config['grant_type'])
            ? $config['grant_type']
            : new ClientCredentials($config['client'], $config);
        $this->config         = $config;
    }

    /**
     * @return OAuth2Middleware
     */
    public function getOauthMiddleware()
    {
        $oath2 = new OAuth2Middleware($this->config['grant_type']);

        $oath2->setTokenPersistence($this->config['storage']);

        return $oath2;
    }
}
