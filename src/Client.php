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

namespace Shopgate\ConnectSdk;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Shopgate\ConnectSdk\DTO\Base;

class Client implements IClient
{
    /**
     * @var ClientInterface
     */
    private $guzzleClient;

    /**
     * Client constructor.
     *
     * @param ClientInterface $guzzleClient
     */
    public function __construct(ClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function doRequest(array $params)
    {
        if ($params['method'] !== 'get' || (isset($params['requestType']) && $params['requestType'] !== 'direct')) {
            return $this->triggerEvent($params);
        }

        try {
            $response = $this->guzzleClient->request(
                $params['method'],
                $params['path'],
                [
                    'query' => array_merge($params['query'], ['service' => $params['service']])
                ]
            );
        } catch (GuzzleException $e) {
            //todo-sg: exception handling
            echo $e->getMessage();
        }

        return \GuzzleHttp\json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     */
    private function triggerEvent(array $params)
    {
        //todo-sg: needs to handle DTOs properly instead of flat array structures
        $values = [$params['body']];
        if ($params['action'] === 'create') {
            $key    = array_keys($params['body'])[0];
            $values = $params['body'][$key];
        }

        $events = [];
        foreach ($values as $value) {
            $event = [
                'event'   => 'entity' . ucfirst($params['action']) . "d",
                'entity'  => $params['entity'],
                'payload' => $value instanceof Base ? $value->toJson() : (array) $value
                // todo-sg: issue with empty objects representing as arrays, instead of {}
            ];

            if (!empty($params['entityId'])) {
                $event['entityId'] = $params['entityId'];
            }
            $events[] = $event;
        }

        try {
            $this->guzzleClient->request(
                $params['method'],
                'events',
                [
                    'json'        => ['events' => $events],
                    'http_errors' => false
                ]
            );
        } catch (GuzzleException $e) {
            //todo-sg: handle exception
            echo $e->getMessage();
        }
    }
}
