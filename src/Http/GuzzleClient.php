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
use function GuzzleHttp\uri_template;

class GuzzleClient extends Client implements ClientInterface
{
    /**
     * Rewritten to resolve base_uri templates
     *
     * @inheritDoc
     */
    public function __construct(array $config = [])
    {
        if (isset($config['base_uri'])) {
            $config['base_uri'] = $this->resolveTemplate($config['base_uri'], $config);
        }
        parent::__construct($config);
    }

    /**
     * Resolves the template style strings
     *
     * @param string $component
     * @param array  $options
     *
     * @return string
     */
    public function resolveTemplate($component, array $options = [])
    {
        return uri_template(urldecode($component), array_merge($this->getConfig() ? : [], $options));
    }
}
