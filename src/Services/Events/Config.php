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

use Shopgate\ConnectSdk\Http\ClientInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    /**
     * @param array $config
     *
     * @return array
     */
    public function resolveMainOptions(array $config)
    {
        $resolver = new OptionsResolver();
        $this->mainDefaultOptions($resolver);

        return $resolver->resolve($config);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function resolveHttpOptions(array $options)
    {
        $httpResolver = new OptionsResolver();
        $this->httpDefaultOptions($httpResolver);

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
                'service'  => 'omni-event-receiver'
            ]
        );
        $resolver->setDefined(['merchantCode', 'auth', 'handler']);
        $resolver->setAllowedValues('env', ['pg', 'dev', '']);
        $resolver->setAllowedTypes('auth', 'string[]');
        $resolver->setAllowedTypes('merchantCode', 'string');
        $resolver->setAllowedTypes('ver', 'int');
        $resolver->setAllowedTypes('handler', ['object', 'null']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function mainDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'http'        => [],
                'http_client' => null,
            ]
        );
        $resolver->setDefined(['http_client']);
        $resolver->setAllowedTypes('http', 'array');
        $resolver->setAllowedTypes('http_client', [ClientInterface::class, 'null']);
    }
}
