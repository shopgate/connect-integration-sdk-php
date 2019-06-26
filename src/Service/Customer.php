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

use Shopgate\ConnectSdk\Exception;
use Shopgate\ConnectSdk\Dto\Customer\Attribute;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Dto\Meta;

class Customer
{
    /** @var ClientInterface */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $query
     *
     * @return Attribute\GetList
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function getAttributes(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = \GuzzleHttp\json_encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'omni-customer',
                'method'  => 'get',
                'path'    => 'attributes',
                'query'   => $query,
            ]
        );
        $response = json_decode($response->getBody(), true);

        $attributes = [];
        foreach ($response['attributes'] as $attribute) {
            $attributes[] = new Attribute\Get($attribute);
        }
        $response['meta']       = new Meta($response['meta']);
        $response['attributes'] = $attributes;

        return new Attribute\GetList($response);
    }

    /**
     * @param string $attributeCode
     * @param array $query
     *
     * @return Attribute\Get
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function getAttribute($attributeCode, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'omni-customer',
                'method'  => 'get',
                'path'    => 'attributes/' . $attributeCode,
                'query'   => $query,
            ]
        );

        $response = json_decode($response->getBody(), true);

        return new Attribute\Get($response['attribute']);
    }

    /**
     * @param Attribute\Create[] $attributes
     * @param array              $query
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function addAttributes(array $attributes, array $query = [])
    {
        $requestAttributes = [];
        foreach ($attributes as $attribute) {
            $requestAttributes[] = $attribute->toArray();
        }

        return $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['attributes' => $requestAttributes],
                'query'       => $query,
                // direct
                'service'     => 'omni-customer',
                'path'        => 'attributes',
                // async
                'entity'      => 'attribute',
                'action'      => 'create',
            ]
        );
    }

    /**
     * @param string           $attributeCode
     * @param Attribute\Update $attribute
     * @param array            $query
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function updateAttribute($attributeCode, Attribute\Update $attribute, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'attributes/' . $attributeCode,
                'entity'      => 'attribute',
                'query'       => $query,
                // direct only
                'action'      => 'update',
                'body'        => $attribute,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $attributeCode,
            ]
        );
    }

    /**
     * @param string $attributeCode
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws Exception\RequestException
     * @throws Exception\NotFoundException
     * @throws Exception\UnknownException
     */
    public function deleteAttribute($attributeCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'delete',
                'path'        => 'attributes/' . $attributeCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $attributeCode,
                'query'       => $query,
            ]
        );
    }
}
