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

class Notification
{
    const NAME = 'notification';

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
     * @param array $settings
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function updateSettings(array $settings, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'settings',
            'body' => ['settings' => $settings],
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
    public function getSettings(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'settings',
            'query' => $query
        ]);

        return isset($response['settings']) ? $response['settings'] : null;
    }

    /**
     * @param string $key
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
    public function getSetting($key, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'settings/' . $key,
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
    public function getTemplates(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'templates',
            'query' => $query
        ]);
    }

    /**
     * @param array $templates
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
     */
    public function addTemplates(array $templates, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'templates',
            'json' => true,
            'body' => ['templates' => $templates],
            'query' => $query
        ]);
    }

    /**
     * @param string $code
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
    public function getTemplate($code, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'templates/' . $code,
            'query' => $query
        ]);

        return isset($response['template']) ? $response['template'] : null;
    }

    /**
     * @param string $templateCode
     * @param array $template
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function updateTemplate($templateCode, array $template, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'templates/' . $templateCode,
            'body' => $template,
            'query' => $query
        ]);
    }

    /**
     * @param string $templateCode
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function deleteTemplate($templateCode, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'templates/' . $templateCode,
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
    public function getCampaigns(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        return $this->client->request([
            'service' => self::NAME,
            'path' => 'campaigns',
            'query' => $query
        ]);
    }

    /**
     * @param array $campaigns
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
     */
    public function addCampaigns(array $campaigns, array $query = [])
    {
        return $this->client->request([
            'method' => 'post',
            'service' => self::NAME,
            'path' => 'campaigns',
            'json' => true,
            'body' => ['campaigns' => $campaigns],
            'query' => $query
        ]);
    }

    /**
     * @param string $code
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
    public function getCampaign($code, array $query = [])
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'path' => 'campaigns/' . $code,
            'query' => $query
        ]);

        return isset($response['campaign']) ? $response['campaign'] : null;
    }

    /**
     * @param string $campaignCode
     * @param array $campaign
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function updateCampaign($campaignCode, array $campaign, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'campaigns/' . $campaignCode,
            'body' => $campaign,
            'query' => $query
        ]);
    }

    /**
     * @param string $campaignCode
     * @param array $query
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function deleteCampaign($campaignCode, array $query = [])
    {
        $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'campaigns/' . $campaignCode,
            'query' => $query
        ]);
    }
}
