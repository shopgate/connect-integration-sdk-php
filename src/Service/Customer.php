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
use Shopgate\ConnectSdk\Dto\Customer\Wishlist;
use Shopgate\ConnectSdk\Dto\Customer\Wishlist\Dto\Item as WishlistItem;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

class Customer
{
    const SERVICE_CUSTOMER = 'customer';

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
        $this->client = $client;
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
     * @throws InvalidDataTypeException
     */
    public function getAttributes(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'attributes',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $attributes = [];
        foreach ($response['attributes'] as $attribute) {
            $attributes[] = new Attribute\Get($attribute);
        }
        $response['meta'] = new Meta($response['meta']);
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
     * @throws InvalidDataTypeException
     */
    public function getAttribute($code, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'attributes/' . $code,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function addAttributes(array $attributes, array $query = [])
    {
        $requestAttributes = [];
        foreach ($attributes as $attribute) {
            $requestAttributes[] = $attribute->toArray();
        }

        return $this->client->doRequest(
            [
                'method' => 'post',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'json' => ['attributes' => $requestAttributes],
                'query' => $query,
                'service' => self::SERVICE_CUSTOMER,
                'path' => 'attributes',
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
     * @throws InvalidDataTypeException
     */
    public function updateAttribute($code, Attribute\Update $attribute, array $query = [])
    {
        return $this->client->doRequest(
            [
                // general
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'attributes/' . $code,
                'entity' => 'attribute',
                'query' => $query,
                'action' => 'update',
                'json' => $attribute,
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
     * @throws InvalidDataTypeException
     */
    public function deleteAttribute($code, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'delete',
                'path' => 'attributes/' . $code,
                'entity' => 'attribute',
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function addAttributeValue(
        $code,
        array $attributeValues,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'attributes/' . $code . '/values/',
                'entity' => 'attributes',
                'action' => 'create',
                'json' => ['values' => $attributeValues],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function updateAttributeValue(
        $code,
        $valueCode,
        AttributeValue\Update $attributeValue,
        array $query = []
    ) {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'attributes/' . $code . '/values/' . $valueCode,
                'entity' => 'attribute',
                'action' => 'update',
                'json' => $attributeValue,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function deleteAttributeValue($code, $valueCode, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'delete',
                'path' => 'attributes/' . $code . '/values/' . $valueCode,
                'entity' => 'attribute',
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function getCustomers(array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }

        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'customers',
                'query' => $query,
            ]
        );
        $response = $this->jsonHelper->decode($response->getBody(), true);

        $customers = [];
        foreach ($response['customers'] as $attribute) {
            $customers[] = new CustomerDto\Get($attribute);
        }
        $response['meta'] = new Meta($response['meta']);
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
     * @throws InvalidDataTypeException
     */
    public function getCustomer($id, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'customers/' . $id,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new CustomerDto\Get($response['customer']);
    }

    /**
     * @param CustomerDto\Create[] $customers
     * @param array                $query
     * @param string               $requestType
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addCustomers(array $customers, array $query = [], $requestType = ShopgateSdk::REQUEST_TYPE_DIRECT)
    {
        $requestCustomers = [];
        foreach ($customers as $customer) {
            $requestCustomers[] = $customer->toArray();
        }

        $response = $this->client->doRequest(
            [
                'method' => 'post',
                'requestType' => $requestType,
                'json' => ['customers' => $requestCustomers],
                'query' => $query,
                'service' => self::SERVICE_CUSTOMER,
                'path' => 'customers',
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
     * @throws InvalidDataTypeException
     */
    public function updateCustomer($id, CustomerDto\Update $customer, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $id,
                'entity' => 'customer',
                'query' => $query,
                'action' => 'update',
                'json' => $customer,
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
     * @throws InvalidDataTypeException
     */
    public function deleteCustomer($id, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'delete',
                'path' => 'customers/' . $id,
                'entity' => 'customer',
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function addContacts($id, array $contacts, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $id . '/contacts',
                'entity' => 'contact',
                'action' => 'create',
                'json' => ['contacts' => $contacts],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function updateContact($id, $customerId, Contact\Update $contact, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $customerId . '/contacts/' . $id,
                'entity' => 'contact',
                'action' => 'update',
                'json' => $contact,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'entityId' => $id,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function deleteContact($id, $customerId, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'delete',
                'path' => 'customers/' . $customerId . '/contacts/' . $id,
                'entity' => 'customer',
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
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
     * @throws InvalidDataTypeException
     */
    public function addNotes($customerId, array $notes, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $customerId . '/notes',
                'json' => ['notes' => $notes],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query
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
     * @throws InvalidDataTypeException
     */
    public function getNotes($customerId, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'customers/' . $customerId . '/notes',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Note\GetList($response);
    }

    /**
     * @param string            $id customer id
     * @param Wishlist\Create[] $wishlists
     * @param array             $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addWishlists($id, array $wishlists, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $id . '/wishlists',
                'entity' => 'wishlist',
                'action' => 'create',
                'json' => ['wishlists' => $wishlists],
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string          $id wishlist id
     * @param string          $customerId
     * @param Wishlist\Update $wishlist
     * @param array           $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function updateWishlist($id, $customerId, Wishlist\Update $wishlist, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $customerId . '/wishlists/' . $id,
                'entity' => 'wishlist',
                'action' => 'update',
                'json' => $wishlist,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'entityId' => $id,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $id wishlist id
     * @param string $customerId
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteWishlist($id, $customerId, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'delete',
                'path' => 'customers/' . $customerId . '/wishlists/' . $id,
                'entity' => 'wishlist',
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $customerId
     * @param array  $query
     *
     * @return Wishlist\GetList
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getWishlists($customerId, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'customers/' . $customerId . '/wishlists',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Wishlist\GetList($response);
    }

    /**
     * @param string $id wishlist id
     * @param string $customerId
     * @param array  $query
     *
     * @return Wishlist\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function getWishlist($id, $customerId, array $query = [])
    {
        $response = $this->client->doRequest(
            [
                // direct only
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'get',
                'path' => 'customers/' . $customerId . '/wishlists/' . $id,
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        return new Wishlist\Get($response);
    }

    /**
     * @param string                $id customer id
     * @param string                $wishlistId
     * @param WishlistItem\Create[] $items
     * @param array                 $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function addWishlistItems($id, $wishlistId, array $items, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'post',
                'path' => 'customers/' . $id . '/wishlists/' . $wishlistId . '/items',
                'entity' => 'wishlistItem',
                'action' => 'create',
                'json' => $items,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }

    /**
     * @param string $id item id
     * @param string $wishlistId
     * @param string $customerId
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function deleteWishlistItem($id, $wishlistId, $customerId, array $query = [])
    {
        return $this->client->doRequest(
            [
                'service' => self::SERVICE_CUSTOMER,
                'method' => 'delete',
                'path' => 'customers/' . $customerId . '/wishlists/' . $wishlistId . '/items/' . $id,
                'entity' => 'wishlistItem',
                'action' => 'delete',
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'query' => $query,
            ]
        );
    }
}
