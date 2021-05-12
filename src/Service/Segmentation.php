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
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Dto\Segmentation\CursorPagination;
use Shopgate\ConnectSdk\Dto\Segmentation\Import;
use Shopgate\ConnectSdk\Dto\Segmentation\Member;
use Shopgate\ConnectSdk\Dto\Segmentation\Segment;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

class Segmentation
{
    const SERVICE_NAME = 'segmentation';

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
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getSegments(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'method' => 'get',
                'path' => 'segments',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        if (isset($response['meta'])) {
            $response['meta'] = new Meta($response['meta']);
        }

        $response['segments'] = array_map(function ($entity) {
            return new Segment\Get($entity);
        }, $response['segments']);

        return $response;
    }


    /**
     * @param Segment\Create[] $segments
     * @param array $query
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addSegments(array $segments = [], array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'post',
                'path' => 'segments',
                'json' => ['segments' => $segments],
            ]
        );
    }


    /**
     * @param string $segmentCode
     * @param array $query
     *
     * @return Segment\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getSegment($segmentCode, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'method' => 'get',
                'path' => 'segments/' . $segmentCode,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);
        return new Segment\Get($response['segment']);
    }


    /**
     * @param string $segmentCode
     * @param Segment\Update $update
     * @param array $query
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function updateSegment($segmentCode, Segment\Update $update, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'post',
                'path' => 'segments/' . $segmentCode,
                'json' => $update,
            ]
        );
    }


    /**
     * @param string $segmentCode
     * @param array $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteSegment($segmentCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'service' => self::SERVICE_NAME,
                'method' => 'delete',
                'path' => 'segments/' . $segmentCode,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $segmentCode
     * @param Segment\CloneSegment $cloneSegment
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function cloneSegment($segmentCode, Segment\CloneSegment $cloneSegment)
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'post',
                'path' => 'segments/' . $segmentCode . '/clone',
                'json' => $cloneSegment,
            ]
        );
    }

    /**
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function searchMembers(array $query = [])
    {
        if (isset($query['rules'])) {
            $query['rules'] = $this->jsonHelper->encode($query['rules']);
        }
        $response = $this->client->doRequest(
            [
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'service' => self::SERVICE_NAME,
                'method' => 'get',
                'path' => 'membersSearch',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        if (isset($response['meta'])) {
            $response['meta'] = new Meta($response['meta']);
        }

        $response['members'] = array_map(function ($entity) {
            return new Member\GetFull($entity);
        }, $response['members']);

        return $response;
    }


    /**
     * @param string $segmentCode
     * @param array $data
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addMembersByFilter($segmentCode, array $data = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'post',
                'path' => 'segments/' . $segmentCode . '/members/addByFilter',
                'json' => $data,
            ]
        );
    }

    /**
     * @param string $segmentCode
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getSegmentMembers($segmentCode, array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'get',
                'path' => 'segments/' . $segmentCode . '/members',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        if (isset($response['meta'])) {
            $response['meta'] = new Meta($response['meta']);
        }

        $response['members'] = array_map(function ($entity) {
            return new Member\GetFull($entity);
        }, $response['members']);

        return $response;
    }


    /**
     * @param string $segmentCode
     * @param Member\Add[] $members
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addSegmentMembers($segmentCode, array $members)
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'post',
                'path' => 'segments/' . $segmentCode . '/members',
                'json' => ['members' => $members],
            ]
        );
    }


    /**
     * @param string $segmentCode * @param array  $query
     *
     * @param Member\Delete[] $members
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteSegmentMembers($segmentCode, array $members)
    {
        return $this->client->doRequest(
            [
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'service' => self::SERVICE_NAME,
                'method' => 'delete',
                'path' => 'segments/' . $segmentCode . '/members',
                'json' => ['members' => $members],
            ]
        );
    }

    /**
     * @param string $customerId
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getCustomerSegments($customerId, array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'get',
                'path' => 'customers/' . urlencode($customerId) . '/segments',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        if (isset($response['meta'])) {
            $response['meta'] = new CursorPagination($response['meta']);
        }

        $response['segments'] = array_map(function ($entity) {
            return new Segment\Get($entity);
        }, $response['segments']);

        return $response;
    }
}
