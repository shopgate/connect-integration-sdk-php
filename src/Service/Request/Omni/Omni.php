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

namespace Shopgate\CloudIntegrationSdk\Service\Request;

use Psr\Http\Message\RequestInterface;
use Shopgate\CloudIntegrationSdk\Client;
use Shopgate\CloudIntegrationSdk\Repository\Config;
use Shopgate\CloudIntegrationSdk\Service\Request\Omni\ValueObject\Event;

class Omni implements RequestServiceInterface
{
    // TODO-sg: maybe move out constants into special service config
    const BASE_URI = 'https://shopgate.com';
    const VERSION = '/v1';
    // TODO-sg: merchantCode needs to be replacable
    const ENDPOINT_PATH = "/merchants/{merchantCode}/events";

    /** @var Client\ClientInterface */
    private $client;

    /**
     * @param Client\ClientInterface $client
     * @param Config\ConfigInterface $config
     */
    public function __construct(
        Client\ClientInterface $client,
        Config\ConfigInterface $config
    ) {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function handle(RequestInterface $request, $uriParams = [])
    {
        $request->withUri(new \GuzzleHttp\Psr7\Uri(self::BASE_URI . self::VERSION . self::ENDPOINT_PATH));

        return $this->client->request($request, $uriParams);
    }

    /**
     * TODO-sg: remove as it is just for proof of concept
     */
    public function update($entityType, $entityId, $data)
    {
        $updateEvent = new Omni\ValueObject\EntityUpdate(
            $entityType,
            $entityId,
            $data
        );

        $request = new \GuzzleHttp\Psr7\Request();
        $request->withMethod('POST')
            ->withBody(\GuzzleHttp\Psr7\stream_for(http_build_query($updateEvent->toArray())));

        return $this->handle($request);
    }
}
