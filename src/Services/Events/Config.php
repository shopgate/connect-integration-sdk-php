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

namespace Shopgate\ConnectSdk\Services\Events;

use kamermans\OAuth2\GrantType\GrantTypeInterface;
use kamermans\OAuth2\Persistence\TokenPersistenceInterface;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    /**
     * @param array $options
     *
     * @return array
     */
    public function resolveMainOptions(array $options)
    {
        $httpResolver = new OptionsResolver();
        $this->httpDefaultOptions($httpResolver);

        return $httpResolver->resolve($options);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function resolveOauthOptions(array $options)
    {
        $httpResolver = new OptionsResolver();
        $this->oauthDefaultOptions($httpResolver);

        return $httpResolver->resolve($options);
    }

    /**
     * These options get injected directly into the HTTP Client
     *
     * @param OptionsResolver $resolver
     */
    private function httpDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'base_uri' => 'https://{service}.shopgate{env}.services/v{ver}/merchants/{merchantCode}/',
                'env'      => '',
                'ver'      => 1,
                'service'  => 'omni-event-receiver',
                'auth'     => 'oauth',
                'oauth'    => []
            ]
        );

        $typeList = [
            'clientId'         => 'string',
            'clientSecret'     => 'string',
            'auth'             => ['string[]', 'string'],
            'merchantCode'     => 'string',
            'ver'              => 'int',
            'handler'          => ['object', 'null'],
            'connect_timeout'  => ['float'],
            'read_timeout'     => ['float'],
            'timeout'          => ['float'],
            'allow_redirects'  => ['bool'],
            'cert'             => ['string', 'string[]'],
            'debug'            => ['bool', 'object'],
            'delay'            => ['int', 'float'],
            'force_ip_resolve' => ['string'],
            'headers'          => ['string[]'],
            'http_errors'      => ['bool'],
            'synchronous'      => ['bool'],
            'verify'           => ['bool', 'string'],
            'version'          => ['float', 'string'],
            'proxy'            => ['string[]', 'string'],
            'http_client'      => [ClientInterface::class, 'null']
        ];
        $resolver->setDefined(array_keys($typeList));
        $resolver->setAllowedValues('env', ['pg', 'dev', '']);

        foreach ($typeList as $key => $type) {
            $resolver->setAllowedTypes($key, $type);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function oauthDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'base_uri'     => 'https://auth.shopgate{env}.services/oauth/token',
                'storage_path' => './access_token.json'
            ]
        );

        $typeList = [
            'client_id'     => 'string',
            'client_secret' => 'string',
            'scope'         => 'string',
            'time'          => 'string',
            'client'        => [\GuzzleHttp\Client::class, ClientInterface::class, 'null'],
            'storage'       => [TokenPersistenceInterface::class, 'null'],
            'grant_type'    => [GrantTypeInterface::class, 'null'],
            'storage_path'  => ['string', null]
        ];
        $resolver->setDefined(array_keys($typeList));
        foreach ($typeList as $key => $type) {
            $resolver->setAllowedTypes($key, $type);
        }
    }
}
