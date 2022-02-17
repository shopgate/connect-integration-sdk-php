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

use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;

class Customer
{
    const NAME = 'customer';

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
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/getAttributes
     */
    public function getAttributes(array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'attributes',
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/getAttribute
     */
    public function getAttribute($attributeCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'attributes/' . $attributeCode,
            'query' => $query
        ]);
    }

    /**
     * @param array $attributes
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/createAttributes
     */
    public function addAttributes(array $attributes, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'attributes',
            'body' => ['attributes' => $attributes],
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array  $attribute
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/updateAttribute
     */
    public function updateAttribute($attributeCode, array $attribute, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'attributes/' . $attributeCode,
            'body' => $attribute,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/deleteAttribute
     */
    public function deleteAttribute($attributeCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'attributes/' . $attributeCode,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param array  $attributeValues
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/addAttributeValues
     */
    public function addAttributeValues($attributeCode, array $attributeValues, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'attributes/' . $attributeCode . '/values',
            'body' => ['values' => $attributeValues],
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param string $attributeValueCode
     * @param array  $attributeValue
     * @param array                 $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/updateAttributeValue
     */
    public function updateAttributeValue($attributeCode, $attributeValueCode, array $attributeValue, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
            'body' => $attributeValue,
            'query' => $query
        ]);
    }

    /**
     * @param string $attributeCode
     * @param string $attributeValueCode
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Attributes/deleteAttributeValue
     */
    public function deleteAttributeValue($attributeCode, $attributeValueCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'attributes/' . $attributeCode . '/values/' . $attributeValueCode,
            'query' => $query
        ]);
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
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/default/getCustomers
     */
    public function getCustomers(array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers',
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/default/getCustomer
     */
    public function getCustomer($customerId, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers/' . $customerId,
            'query' => $query
        ]);
    }

    /**
     * @param array $customers
     * @param array $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/default/createCustomers
     */
    public function addCustomers(array $customers, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers',
            'method' => 'post',
            'body' => ['customers' => $customers],
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $customer
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/default/updateCustomer
     */
    public function updateCustomer($customerId, array $customer, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId,
            'body' => $customer,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/default/deleteCustomer
     */
    public function deleteCustomer($customerId, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'customers/' . $customerId,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $contacts
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Contacts/addCustomerContacts
     */
    public function addContacts($customerId, array $contacts, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId . '/contacts',
            'body' => ['contacts' => $contacts],
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $contactId
     * @param array  $contact
     * @param array          $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Contacts/updateCustomerContact
     */
    public function updateContact($customerId, $contactId, array $contact, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId . '/contacts/' . $contactId,
            'body' => $contact,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $contactId
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Contacts/deleteCustomerContact
     */
    public function deleteContact($customerId, $contactId, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'customers/' . $customerId . '/contacts/' . $contactId,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $notes
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Notes/post_merchants__merchantCode__customers__customerId__notes
     */
    public function addNotes($customerId, array $notes, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId . '/notes',
            'body' => ['notes' => $notes],
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Notes/get_merchants__merchantCode__customers__customerId__notes
     */
    public function getNotes($customerId, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers/' . $customerId . '/notes',
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $wishlists
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/post_merchants__merchantCode__customers__customerId__wishlists
     */
    public function addWishlists($customerId, array $wishlists, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId . '/wishlists',
            'body' => ['wishlists' => $wishlists],
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $wishlistCode
     * @param array  $wishlist
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/post_merchants__merchantCode__customers__customerId__wishlists__wishlistCode_
     */
    public function updateWishlist($customerId, $wishlistCode, $wishlist, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId . '/wishlists/' . $wishlistCode,
            'body' => $wishlist,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $wishlistCode
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/delete_merchants__merchantCode__customers__customerId__wishlists__wishlistCode_
     */
    public function deleteWishlist($customerId, $wishlistCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'customers/' . $customerId . '/wishlists/' . $wishlistCode,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/get_merchants__merchantCode__customers__customerId__wishlists
     */
    public function getWishlists($customerId, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers/' . $customerId . '/wishlists',
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $wishlistCode
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/get_merchants__merchantCode__customers__customerId__wishlists__wishlistCode_
     */
    public function getWishlist($customerId, $wishlistCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'path' => 'customers/' . $customerId . '/wishlists/' . $wishlistCode,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $wishlistCode
     * @param array  $items
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/post_merchants__merchantCode__customers__customerId__wishlists__wishlistCode__items
     */
    public function addWishlistItems($customerId, $wishlistCode, array $items, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'customers/' . $customerId . '/wishlists/' . $wishlistCode . '/items',
            'body' => $items,
            'query' => $query
        ]);
    }

    /**
     * @param string $customerId
     * @param string $wishlistCode
     * @param string $productCode
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     *
     * @see https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/customer-crud.yaml#/Wishlist/delete_merchants__merchantCode__customers__customerId__wishlists__wishlistCode__items__productCode_
     */
    public function deleteWishlistItem($customerId, $wishlistCode, $productCode, array $query = [])
    {
        return $this->client->request([
            'service' => self::NAME,
            'method' => 'delete',
            'path' => 'customers/' . $customerId . '/wishlists/' . $wishlistCode . '/items/' . $productCode,
            'query' => $query
        ]);
    }
}
