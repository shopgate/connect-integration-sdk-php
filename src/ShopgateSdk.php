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

use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Service\BulkImport;
use Shopgate\ConnectSdk\Service\Catalog;
use Shopgate\ConnectSdk\Service\Customer;
use Shopgate\ConnectSdk\Service\Location;

class ShopgateSdk
{
    const REQUEST_TYPE_DIRECT = 'direct';
    const REQUEST_TYPE_EVENT  = 'event';
    const REGISTERED_SERVICES = ['catalog', 'customer', 'bulkImport'];

    /** @var ClientInterface */
    private $client;

    /** @var Catalog */
    private $catalog;

    /** @var Customer */
    private $customer;

    /** @var Location */
    private $location;

    /** @var BulkImport */
    private $bulkImport;

    /** @var Json */
    private $jsonHelper;

    /**
     * The $config argument is a list of key-value pairs:
     *
     * clientId     => your app's client ID for authentication
     * clientSecret => your app's client secret for authentication
     * merchantCode => the merchant code at Shopgate that you want to sync to/from
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->client = isset($config['client'])
            ? $config['client']
            : Client::createInstance(
                $config['clientId'],
                $config['clientSecret'],
                $config['merchantCode'],
                null,
                isset($config['env']) ? $config['env'] : ''
            );
        $this->jsonHelper = new Json();

        if (isset($config['services'])) {
            $this->setServices($config['services']);
        }
    }

    /**
     * @return Catalog
     */
    public function getCatalogService()
    {
        if (!$this->catalog) {
            $this->catalog = new Catalog($this->client, $this->jsonHelper);
        }

        return $this->catalog;
    }

    /**
     * @return Customer
     */
    public function getCustomerService()
    {
        if (!$this->customer) {
            $this->customer = new Customer($this->client, $this->jsonHelper);
        }

        return $this->customer;
    }

    /**
     * @return Location
     */
    public function getLocationService()
    {
        if (!$this->location) {
            $this->location = new Location($this->client, $this->jsonHelper);
        }

        return $this->location;
    }

    /**
     * @return BulkImport
     */
    public function getBulkImportService()
    {
        if (!$this->bulkImport) {
            $this->bulkImport = new BulkImport($this->client);
        }

        return $this->bulkImport;
    }

    /**
     * @return ClientInterface|Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param object[] $serviceArgs [string, object]
     */
    private function setServices($serviceArgs)
    {
        foreach (self::REGISTERED_SERVICES as $service) {
            if (!isset($serviceArgs[$service])) {
                continue;
            }

            $this->$service = $serviceArgs[$service];
        }
    }
}
