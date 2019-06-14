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

use Dto\Exceptions\InvalidDataTypeException;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Async\Factory;
use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;

class Client implements ClientInterface
{
    /**
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    /**
     * Client constructor.
     *
     * @param GuzzleClientInterface $guzzleClient
     */
    public function __construct(GuzzleClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param array $params
     *
     * @return ResponseInterface
     * @throws RequestException
     * @throws UnknownException
     */
    public function doRequest(array $params)
    {
        if (!$this->isDirect($params)) {
            return $this->triggerEvent($params);
        }
        $response = null;
        $body = isset($params['body'])
            ? $params['body']
            : [];
        try {
            $response = $this->guzzleClient->request(
                $params['method'],
                $params['path'],
                [
                    'query' => ['service' => $params['service']] + (isset($params['query'])
                            ? $params['query']
                            : []),
                    'json' => $body instanceof Base
                        ? $body->toJson()
                        : (new Base($body))->toJson(),
                ]
            );
        } catch (GuzzleRequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            throw new RequestException(
                $statusCode,
                $e->getResponse() && $e->getResponse()->getBody() ? $e->getResponse()->getBody()->getContents() : $e->getMessage()
            );
        } catch (GuzzleException $e) {
            throw new UnknownException($e->getMessage());
        } catch (\Exception $e) {
            throw new UnknownException($e->getMessage());
        }

        return $response;
    }

    /**
     * @param array $params
     *
     * @return ResponseInterface
     * @throws RequestException
     * @throws UnknownException
     */
    private function triggerEvent(array $params)
    {
        $values = [
            isset($params['body'])
                ? $params['body']
                : new Base(),
        ];
        if ($params['action'] === 'create') {
            $key = array_keys($params['body'])[0];
            $values = $params['body'][$key];
        }

        $factory = new Factory();
        foreach ($values as $payload) {
            $entityId = isset($params['entityId'])
                ? $params['entityId']
                : null;
            $factory->addEvent($params['action'], $params['entity'], $payload, $entityId);
        }

        try {
            return $this->guzzleClient->request(
                'post',
                'events',
                [
                    'json' => $factory->getRequest()->toJson(),
                    'http_errors' => false,
                ]
            );
        } catch (GuzzleRequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            throw new RequestException(
                $statusCode,
                $e->getResponse() && $e->getResponse()->getBody() ? $e->getResponse()->getBody()->getContents() : $e->getMessage()
            );
        } catch (GuzzleException $e) {
            throw new UnknownException($e->getMessage());
        } catch (\Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     * @todo-sg: unit tests
     */
    public function isDirect(array $params)
    {
        return (!isset($params['requestType']) && $params['method'] === 'get') || $params['requestType'] === ShopgateSdk::REQUEST_TYPE_DIRECT;
    }
}
