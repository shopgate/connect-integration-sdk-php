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

abstract class OrderTest extends ShopgateSdkTest
{
    const CUSTOMER_SERVICE = 'customer';
    const CATALOG_SERVICE = 'catalog';
    const LOCATION_SERVICE = 'location';
    const METHOD_DELETE_CUSTOMER = 'deleteCustomer';
    const METHOD_DELETE_PRODUCT = 'deleteProduct';
    const METHOD_DELETE_LOCATION = 'deleteLocation';
    const LOCATION_CODE = 'integration-test';

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::CUSTOMER_SERVICE,
            $this->sdk->getCustomerService(),
            [
                self::METHOD_DELETE_CUSTOMER => []
            ]
        );

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_PRODUCT => []
            ]
        );

        $this->registerForCleanUp(
            self::LOCATION_SERVICE,
            $this->sdk->getLocationService(),
            [
                self::METHOD_DELETE_LOCATION => []
            ]
        );
    }
}
