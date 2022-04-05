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

namespace Shopgate\ConnectSdk\Service;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\Helper\Json;

class Location
{
    const NAME = 'location';

    /** @var ClientInterface */
    private $client;

    /** @var Json */
    private $jsonHelper;

    /**
     * @param ClientInterface $client
     * @param Json $jsonHelper
     */
    public function __construct(ClientInterface $client, Json $jsonHelper)
    {
        $this->client = $client;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @param array[] $locations
     * @param array $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/location-crud.yaml#/default/post_merchants__merchantCode__locations
     */
    public function addLocations(array $locations, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'locations',
            'json' => true,
            'body' => ['locations' => $locations],
            'query' => $query
        ]);
    }

    /**
     * @param string $code
     * @param array $location
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/location-crud.yaml#/default/post_merchants__merchantCode__locations__locationCode_
     */
    public function updateLocation($code, array $location, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'locations/' . $code,
            'body' => $location,
            'query' => $query
        ]);
    }

    /**
     * @param string $code
     * @param array $query
     *
     * @return array|ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/location-crud.yaml#/default/delete_merchants__merchantCode__locations__locationCode_
     */
    public function deleteLocation($code, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'locations/' . $code,
            'query' => $query
        ]);
    }

    /**
     * @param array $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/location-crud.yaml#/default/get_merchants__merchantCode__locations
     */
    public function getLocations(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'locations',
            'query' => $query
        ]);
    }

    /**
     * @param string $code location code
     * @param array $query
     *
     * @return array|null
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/location-crud.yaml#/default/get_merchants__merchantCode__locations__locationCode_
     */
    public function getLocation($code, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'locations/' . $code,
            'query' => $query
        ]);

        return isset($response['location']) ? $response['location'] : null;
    }
}
