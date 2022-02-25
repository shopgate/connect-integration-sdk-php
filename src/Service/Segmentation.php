<?php

/**
 * Copyright Shopgate GmbH.
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
 * @copyright Shopgate GmbH
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Service;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;

class Segmentation
{
    const NAME = 'segmentation';

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
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws TokenPersistenceException
     */
    public function getSegments(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'segments',
            'query' => $query
        ]);
    }

    /**
     * @param array $segments
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
     */
    public function addSegments(array $segments = [], array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'segments',
            'json' => true,
            'body' => ['segments' => $segments],
            'query' => $query
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function getSegment($segmentCode, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'segments/' . $segmentCode,
            'query' => $query
        ]);

        return isset($response['segment']) ? $response['segment'] : null;
    }

    /**
     * @param string $segmentCode
     * @param array $update
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function updateSegment($segmentCode, array $update, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'segments/' . $segmentCode,
            'body' => $update,
            'query' => $query
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function deleteSegment($segmentCode, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'segments/' . $segmentCode,
            'query' => $query
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $cloneSegment
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function cloneSegment($segmentCode, array $cloneSegment, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'segments/' . $segmentCode . '/clone',
            'body' => $cloneSegment,
            'query' => $query
        ]);
    }

    /**
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function searchMembers(array $query = [])
    {
        if (isset($query['rules'])) {
            $query['rules'] = $this->jsonHelper->encode($query['rules']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'membersSearch',
            'query' => $query
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $data
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function addMembersByFilter($segmentCode, array $data = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'segments/' . $segmentCode . '/members/addByFilter',
            'json' => true,
            'body' => $data
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function getSegmentMembers($segmentCode, array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'segments/' . $segmentCode . '/members',
            'query' => $query
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $members
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function addSegmentMembers($segmentCode, array $members)
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'segments/' . $segmentCode . '/members',
            'json' => true,
            'body' => ['members' => $members]
        ]);
    }

    /**
     * @param string $segmentCode
     * @param array $members
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function deleteSegmentMembers($segmentCode, array $members)
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'segments/' . $segmentCode . '/members',
            'body' => ['members' => $members],
        ]);
    }

    /**
     * @param string $customerId
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function getCustomerSegments($customerId, array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers/' . urlencode($customerId) . '/segments',
            'query' => $query
        ]);
    }
}
