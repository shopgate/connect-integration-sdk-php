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
use Shopgate\ConnectSdk\Dto\Customer\Attribute;
use Shopgate\ConnectSdk\Dto\Customer\Customer as CustomerDto;
use Shopgate\ConnectSdk\Dto\Customer\AttributeValue;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
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
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
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
     * @param string $code attribute code
     * @param array  $query
     *
     * @return Attribute\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getAttribute($code, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'omni-customer',
                'method'  => 'get',
                'path'    => 'attributes/' . $code,
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
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
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
     * @param string           $code attribute code
     * @param Attribute\Update $attribute
     * @param array            $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function updateAttribute($code, Attribute\Update $attribute, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'attributes/' . $code,
                'entity'      => 'attribute',
                'query'       => $query,
                // direct only
                'action'      => 'update',
                'body'        => $attribute,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $code,
            ]
        );
    }

    /**
     * @param string $code attribute code
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteAttribute($code, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'delete',
                'path'        => 'attributes/' . $code,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $code,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string                  $code attribute code
     * @param AttributeValue\Create[] $attributeValues
     * @param array                   $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addAttributeValue(
        $code,
        array $attributeValues,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'attributes/' . $code . '/values/',
                'entity'      => 'attributes',
                'action'      => 'create',
                'body'        => ['values' => $attributeValues],
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string                $code      attribute code
     * @param string                $valueCode attribute value code
     * @param AttributeValue\Update $attributeValue
     * @param array                 $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function updateAttributeValue(
        $code,
        $valueCode,
        AttributeValue\Update $attributeValue,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'attributes/' . $code . '/values/' . $valueCode,
                'entity'      => 'attribute',
                'action'      => 'update',
                'body'        => $attributeValue,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'entityId'    => $code,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string $code      attribute code
     * @param string $valueCode attribute value code
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteAttributeValue($code, $valueCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'delete',
                'path'        => 'attributes/' . $code . '/values/' . $valueCode,
                'entity'      => 'attribute',
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param array $query
     *
     * @return CustomerDto\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getCustomers(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = \GuzzleHttp\json_encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'omni-customer',
                'method'  => 'get',
                'path'    => 'customers',
                'query'   => $query,
            ]
        );
        $response = json_decode($response->getBody(), true);

        $customers = [];
        foreach ($response['customers'] as $attribute) {
            $customers[] = new CustomerDto\Get($attribute);
        }
        $response['meta']       = new Meta($response['meta']);
        $response['attributes'] = $customers;

        return new CustomerDto\GetList($response);
    }

    /**
     * @param string $id customer id
     * @param array  $query
     *
     * @return CustomerDto\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getCustomer($id, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => 'omni-customer',
                'method'  => 'get',
                'path'    => 'customers/' . $id,
                'query'   => $query,
            ]
        );

        $response = json_decode($response->getBody(), true);

        return new CustomerDto\Get($response['customer']);
    }

    /**
     * @param CustomerDto\Create[] $customers
     * @param array                $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addCustomers(array $customers, array $query = [])
    {
        $requestCustomers = [];
        foreach ($customers as $customer) {
            $requestCustomers[] = $customer->toArray();
        }

        $response = $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                'body'        => ['customers' => $requestCustomers],
                'query'       => $query,
                // direct
                'service'     => 'omni-customer',
                'path'        => 'customers',
                // async
                'entity'      => 'customer',
                'action'      => 'create',
            ]
        );

        $response = json_decode($response->getBody(), true);

        return $response;
    }

    /**
     * @param string             $id customer id
     * @param CustomerDto\Update $customer
     * @param array              $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function updateCustomer($id, CustomerDto\Update $customer, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'customers/' . $id,
                'entity'      => 'customer',
                'query'       => $query,
                // direct only
                'action'      => 'update',
                'body'        => $customer,
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $id,
            ]
        );
    }

    /**
     * @param string $id customer id
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteCustomer($id, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'delete',
                'path'        => 'customers/' . $id,
                'entity'      => 'customer',
                'action'      => 'delete',
                'requestType' => isset($query['requestType'])
                    ? $query['requestType']
                    : ShopgateSdk::REQUEST_TYPE_EVENT,
                // async
                'entityId'    => $id,
                'query'       => $query,
            ]
        );
    }
}
