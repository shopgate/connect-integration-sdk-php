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

namespace Shopgate\CloudIntegrationSdk\Service\Omni;

use Psr\Http\Message\RequestInterface;
use Shopgate\CloudIntegrationSdk\Client;

class Request implements RequestServiceInterface
{
    // TODO-sg: maybe move out constants into special service config
    const BASE_URI = 'https://shopgate.com';
    const VERSION = '/v1';
    // TODO-sg: merchantCode needs to be replacable
    const ENDPOINT_PATH = "/merchants/{merchantCode}/events";

    /** @var Client\ClientInterface */
    private $client;

    /** @var array */
    private $config;

    /**
     * @param Client\ClientInterface    $client
     * @param array                     $config
     */
    public function __construct(
        Client\ClientInterface $client,
        array $config
    ) {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function handle(RequestInterface $request, $uriParams = [])
    {
        return $this->client->request($request, $uriParams);
    }

    /**
     * @param string $entityType
     * @param string $entityId
     * @param array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update($entityType, $entityId, $data)
    {
        $updateEvent = new ValueObject\EntityUpdate(
            $entityType,
            $entityId,
            $data
        );

        $request = new \GuzzleHttp\Psr7\Request(
            'POST',
            new \GuzzleHttp\Psr7\Uri(self::BASE_URI . self::VERSION . self::ENDPOINT_PATH)
        );
        $request->withBody(\GuzzleHttp\Psr7\stream_for(http_build_query($updateEvent->toArray())));

        return $this->handle($request);
    }
}
