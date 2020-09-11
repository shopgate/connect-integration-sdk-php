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

namespace Shopgate\ConnectSdk\Tests\Integration;

use Dotenv\Dotenv;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Catalog\Catalog;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\ShopgateSdk;

abstract class ShopgateSdkUtility extends TestCase
{
    const SLEEP_TIME_AFTER_EVENT = 300000;

    const CATALOG_SERVICE = 'catalog';
    const LOCATION_SERVICE = 'location';
    const CUSTOMER_SERVICE = 'customer';
    const WEBHOOK_SERVICE = 'webhook';

    const METHOD_DELETE_CATEGORY = 'deleteCategory';
    const METHOD_DELETE_PRODUCT = 'deleteProduct';
    const METHOD_DELETE_ATTRIBUTE = 'deleteAttribute';
    const METHOD_DELETE_LOCATION = 'deleteLocation';
    const METHOD_DELETE_INVENTORIES = 'deleteInventories';
    const METHOD_DELETE_RESERVATIONS = 'deleteReservations';
    const METHOD_DELETE_CUSTOMER = 'deleteCustomer';
    const METHOD_DELETE_CATALOG = 'deleteCatalog';
    const METHOD_DELETE_CUSTOMER_ATTRIBUTE = 'deleteAttribute';
    const METHOD_DELETE_CUSTOMER_CONTACT = 'deleteContact';
    const METHOD_DELETE_CUSTOMER_WISHLIST = 'deleteWishlist';
    const METHOD_DELETE_WEBHOOK = 'deleteWebhook';


    const SAMPLE_CATALOG_CODE = 'NARetail';
    const SAMPLE_CATALOG_CODE_NON_DEFAULT = 'NAWholesale';

    /** @var ShopgateSdk */
    protected $sdk;

    /** @var ['$service' => ['$delete_method' => [string, string...]]] */
    protected $services = [];

    /**
     * Main setup before any tests are ran, runs once
     */
    public static function setUpBeforeClass()
    {
        $env = Dotenv::create(__DIR__);
        $env->load();
        $env->required(
            [
                'clientId',
                'clientSecret',
                'merchantCode',
                'username',
                'password',
            ]
        );
    }

    /**
     * Runs before every test
     *
     * @throws Exception
     */
    public function setUp()
    {
        $client = Client::createInstance(
            getenv('clientId'),
            getenv('clientSecret'),
            getenv('merchantCode'),
            getenv('username'),
            getenv('password'),
            getenv('baseUri')
                ?: '',
            getenv('env')
                ?: '',
            getenv('accessTokenPath')
                ?: ''
        );

        if ((int)getenv('requestLogging')) {
            $client->enableRequestLogging(
                new Logger('request_logger_integration_tests', [new StreamHandler('php://stdout')])
            );
        }

        $this->sdk = new ShopgateSdk(['client' => $client]);

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_CATEGORY => ['force' => true],
                self::METHOD_DELETE_PRODUCT => [],
                self::METHOD_DELETE_ATTRIBUTE => [],
                self::METHOD_DELETE_RESERVATIONS => [],
                self::METHOD_DELETE_INVENTORIES => [],
                self::METHOD_DELETE_CATALOG => [],
            ]
        );
        $this->registerForCleanUp(
            self::LOCATION_SERVICE,
            $this->sdk->getLocationService(),
            [
                self::METHOD_DELETE_LOCATION => []
            ]
        );
        $this->registerForCleanUp(
            self::CUSTOMER_SERVICE,
            $this->sdk->getCustomerService(),
            [
                self::METHOD_DELETE_CUSTOMER_CONTACT => [],
                self::METHOD_DELETE_CUSTOMER => [],
                self::METHOD_DELETE_CUSTOMER_ATTRIBUTE => [],
                self::METHOD_DELETE_CUSTOMER_WISHLIST => []
            ]
        );

        $this->registerForCleanUp(
            self::WEBHOOK_SERVICE,
            $this->sdk->getWebhooksService(),
            [
                self::METHOD_DELETE_WEBHOOK => []
            ]
        );
    }

    /**
     * @param string   $serviceKey
     * @param          $service
     * @param string[] $deleteMethods
     */
    protected function registerForCleanUp($serviceKey, $service, $deleteMethods)
    {
        $this->services[$serviceKey]['service'] = $service;
        foreach ($deleteMethods as $deleteMethod => $parameters) {
            $this->services[$serviceKey][$deleteMethod] = [];
            $this->services[$serviceKey][$deleteMethod]['ids'] = [];
            $this->services[$serviceKey][$deleteMethod]['parameters'] = $parameters;
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->services as $service) {
            foreach ($service as $deleteMethodName => $values) {
                if (!is_array($values)) {
                    continue;
                }
                foreach ($values['ids'] as $parameters) {
                    try {
                        call_user_func_array([$service['service'], $deleteMethodName], $parameters);
                    } catch (\Exception $err) {
                        // ignore not found errors as this actually is about deletions
                        if (!($err instanceof \Shopgate\ConnectSdk\Exception\NotFoundException)) {
                            echo 'Not a 404: ' . print_r(get_class($err), true);
                            throw $err;
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws InvalidDataTypeException
     *
     * @throws \Shopgate\ConnectSdk\Exception\Exception
     */
    protected function createDefaultCatalogs()
    {
        $catalogs = $this->defaultCatalogs();
        // add to cleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATALOG,
            [$catalogs[0]->getCode(), $catalogs[1]->getCode()]
        );

        $this->sdk->getCatalogService()->addCatalogs($catalogs);

        return $catalogs;
    }

    /**
     * @return Catalog\Create[]
     *
     * @throws InvalidDataTypeException
     */
    protected function defaultCatalogs()
    {
        $catalogs = [];
        $catalogs[] = (new Catalog\Create())
            ->setCode(self::SAMPLE_CATALOG_CODE)
            ->setName('North American Retail')
            ->setDefaultLocaleCode('en-us')
            ->setDefaultCurrencyCode('USD')
            ->setIsDefault(true);
        $catalogs[] = (new Catalog\Create())
            ->setCode(self::SAMPLE_CATALOG_CODE_NON_DEFAULT)
            ->setName('North American Wholesale')
            ->setDefaultLocaleCode('en-us')
            ->setDefaultCurrencyCode('USD')
            ->setIsDefault(false);

        return $catalogs;
    }

    /**
     * @param string      $service
     * @param string      $deleteMethod
     * @param string[]    $entityIds
     * @param null|string $catalogCode
     */
    protected function deleteEntitiesAfterTestRun($service, $deleteMethod, array $entityIds, $catalogCode = null)
    {
        if (empty($entityIds)) {
            return;
        }

        $deleteRequestParameter = [];

        $extraParameter = array_merge(
            $this->services[$service][$deleteMethod]['parameters'],
            ['requestType' => 'direct']
        );
        if ($catalogCode) {
            $extraParameter['catalogCode'] = $catalogCode;
        }
        $deleteRequestParameter[] = $extraParameter;

        foreach ($entityIds as $entityId) {
            $entry = $deleteRequestParameter;

            if (is_array($entityId) && !$entityId[0] instanceof Base) {
                foreach (array_reverse($entityId) as $parameter) {
                    array_unshift($entry, $parameter);
                }
            } else {
                array_unshift($entry, $entityId);
            }

            $this->services[$service][$deleteMethod]['ids'][] = $entry;
        }
    }
}
