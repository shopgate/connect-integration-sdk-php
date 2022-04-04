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

use Shopgate\ConnectSdk\Exception\MissingConfigFieldException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Helper\Value;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Service\BulkImport;
use Shopgate\ConnectSdk\Service\Catalog;
use Shopgate\ConnectSdk\Service\Customer;
use Shopgate\ConnectSdk\Service\Location;
use Shopgate\ConnectSdk\Service\Notification;
use Shopgate\ConnectSdk\Service\Order;
use Shopgate\ConnectSdk\Service\Segmentation;
use Shopgate\ConnectSdk\Service\Webhook;

class ShopgateSdk
{
    const REGISTERED_SERVICES       = ['catalog', 'customer', 'bulkImport'];
    const REQUIRED_CONFIG_FIELDS    = ['clientId', 'clientSecret', 'merchantCode', 'username', 'password'];

    /** @var ClientInterface */
    private $client;

    /** @var Catalog */
    private $catalog;

    /** @var Customer */
    private $customer;

    /** @var Location */
    private $location;

    /** @var Order */
    private $order;

    /** @var Webhook */
    private $webhook;

    /** @var BulkImport */
    private $bulkImport;

    /** @var Notification */
    private $notification;

    /** @var Segmentation */
    private $segmentation;

    /** @var Json */
    private $jsonHelper;

    /**
     * The $config argument is a list of key-value pairs:
     *
     * clientId     => your app's client ID for authentication
     * clientSecret => your app's client secret for authentication
     * merchantCode => the merchant code at Shopgate that you want to sync to/from
     * username     => your username
     * password     => your password
     *
     * @param array $config
     *
     * @throws MissingConfigFieldException
     */
    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->client     = isset($config['client'])
            ? $config['client']
            : Client::createInstance(
                $config['clientId'],
                $config['clientSecret'],
                $config['merchantCode'],
                $config['username'],
                $config['password'],
                Value::elvis($config, 'baseUri', null),
                $this->getEnvironmentByConfig($config)
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
            $this->customer = new Customer($this->client);
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
     * @return Order
     */
    public function getOrderService()
    {
        if (!$this->order) {
            $this->order = new Order($this->client);
        }

        return $this->order;
    }

    /**
     * @return Webhook
     */
    public function getWebhooksService()
    {
        if (!$this->webhook) {
            $this->webhook = new Webhook($this->client, $this->jsonHelper);
        }

        return $this->webhook;
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
     * @return Notification
     */
    public function getNotificationService()
    {
        if (!$this->notification) {
            $this->notification = new Notification($this->client, $this->jsonHelper);
        }

        return $this->notification;
    }

    /**
     * @return Segmentation
     */
    public function getSegmentationService()
    {
        if (!$this->segmentation) {
            $this->segmentation = new Segmentation($this->client, $this->jsonHelper);
        }

        return $this->segmentation;
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

    /**
     * @param array $config
     *
     * @throws MissingConfigFieldException
     */
    private function validateConfig($config)
    {
        if (isset($config['client'])) {
            return;
        }

        if ($missing = array_diff(self::REQUIRED_CONFIG_FIELDS, array_keys($config))) {
            throw new MissingConfigFieldException(
                'Config is missing the following keys: ' .
                implode(', ', $missing)
            );
        }
    }

    private function getEnvironmentByConfig($config)
    {
        switch (Value::elvis($config, 'env', '')) {
            default:
            case 'prod':
            case 'production':
                return '';
            case 'pg':
            case 'staging':
                return 'pg';
            case 'dev':
            case 'development':
                return 'dev';
        }
    }
}
