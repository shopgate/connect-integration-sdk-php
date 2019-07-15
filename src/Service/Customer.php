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
use Shopgate\ConnectSdk\Dto\Customer\AttributeValue;
use Shopgate\ConnectSdk\Dto\Customer\Contact;
use Shopgate\ConnectSdk\Dto\Customer\Customer as CustomerDto;
use Shopgate\ConnectSdk\Dto\Customer\Note;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

class Customer
{
    /** @var ClientInterface */
    private $client;

    /** @var Json */
    private $jsonHelper;

    /**
     * @param ClientInterface $client
     * @param Json            $jsonHelper
     */
    public function __construct(ClientInterface $client, Json $jsonHelper)
    {
        $this->client     = $client;
        $this->jsonHelper = $jsonHelper;
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
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
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
        $response = $this->jsonHelper->decode($response->getBody(), true);

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

        $response = $this->jsonHelper->decode($response->getBody(), true);

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
                'method'      => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'body'        => ['attributes' => $requestAttributes],
                'query'       => $query,
                'service'     => 'omni-customer',
                'path'        => 'attributes',
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
                'action'      => 'update',
                'body'        => $attribute,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
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
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
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
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
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
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
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
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
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
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
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
        $response = $this->jsonHelper->decode($response->getBody(), true);

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

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new CustomerDto\Get($response['customer']);
    }

    /**
     * @param CustomerDto\Create[] $customers
     * @param array                $query
     *
     * @return array
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
                'method'      => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'body'        => ['customers' => $requestCustomers],
                'query'       => $query,
                'service'     => 'omni-customer',
                'path'        => 'customers',
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

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
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'customers/' . $id,
                'entity'      => 'customer',
                'query'       => $query,
                'action'      => 'update',
                'body'        => $customer,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
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
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string           $id customer id
     * @param Contact\Create[] $contacts
     * @param array            $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addContacts($id, array $contacts, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'customers/' . $id . '/contacts',
                'entity'      => 'contact',
                'action'      => 'create',
                'body'        => ['contacts' => $contacts],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string         $id contact id
     * @param string         $customerId
     * @param Contact\Update $contact
     * @param array          $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function updateContact($id, $customerId, Contact\Update $contact, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'customers/' . $customerId . '/contacts/' . $id,
                'entity'      => 'contact',
                'action'      => 'update',
                'body'        => $contact,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'entityId'    => $id,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string $id contact id
     * @param string $customerId
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function deleteContact($id, $customerId, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'delete',
                'path'        => 'customers/' . $customerId . '/contacts/' . $id,
                'entity'      => 'customer',
                'action'      => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query'       => $query,
            ]
        );
    }

    /**
     * @param string        $customerId
     * @param Note\Create[] $notes
     * @param array         $query
     *
     * @return string[]
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function addNotes($customerId, array $notes, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service'     => 'omni-customer',
                'method'      => 'post',
                'path'        => 'customers/' . $customerId . '/notes',
                'body'        => ['notes' => $notes],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query'       => $query
            ]
        );

        return $this->jsonHelper->decode($response->getBody(), true)['ids'];
    }

    /**
     * @param string $customerId
     * @param array  $query
     *
     * @return Note\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function getNotes($customerId, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => 'omni-customer',
                'method'  => 'get',
                'path'    => 'customers/' . $customerId . '/notes',
                'query'   => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Note\GetList($response);
    }
}
