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
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use Psr\Http\Message\UriInterface;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Base;
use function GuzzleHttp\Psr7\uri_for;
use function GuzzleHttp\uri_template;

class GuzzleClient extends Client implements ClientInterface
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!empty($config['oauth'])) {
            $config['base_uri'] = $this->resolveTemplate($config['base_uri']);
            /** @var HandlerStack $handler */
            $handler = $this->getConfig('handler');
            $oauth   = new OAuth($config);
            $handler->push($oauth->getOauthMiddleware());
        }
    }

    /**
     * Resolves the templates
     *
     * @inheritDoc
     * @throws GuzzleException
     */
    public function request($method, $uri = '', array $options = [])
    {
        $baseUri          = $this->resolveUri($uri, $options['query']);
        $options['query'] = $this->cleanInternalMeta($options['query']);

        return parent::request($method, $baseUri, $options);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return Psr7\Uri|UriInterface
     */
    private function resolveUri($uri, array $options = [])
    {
        /** @var Psr7\Uri $baseUri */
        $baseUri = $this->getConfig('base_uri');
        if (!empty($uri)) {
            $uri     = $this->resolveTemplate($uri, $options);
            $baseUri = Psr7\UriResolver::resolve($baseUri, uri_for($uri));
        }
        $baseUri = $baseUri->withHost($this->resolveTemplate($baseUri->getHost(), $options));
        $baseUri = $baseUri->withPath($this->resolveTemplate($baseUri->getPath(), $options));
        $baseUri = $baseUri->withQuery($this->resolveTemplate($baseUri->getQuery(), $options));

        return $baseUri;
    }

    /**
     * Resolves the template style strings
     *
     * @param string $component
     * @param array  $options
     *
     * @return string
     */
    private function resolveTemplate($component, array $options = [])
    {
        return uri_template(urldecode($component), array_merge($this->getConfig() ? : [], $options));
    }

    /**
     * Remove meta that does not need to be sent to the endpoints
     *
     * @param array $meta
     *
     * @return array
     */
    private function cleanInternalMeta(array $meta = [])
    {
        $blacklist = [Base::KEY_TYPE, 'service', 'ver', 'env', 'merchantCode', 'productCode', 'categoryCode'];

        return array_filter(
            $meta,
            static function ($item) use ($blacklist) {
                return !in_array($item, $blacklist, true);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
