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
 * @author    Shopgate Inc, 804 Congress Ave, Austin, Texas 78701
 *            <interfaces@shopgate.com>
 * @copyright Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License,
 *            Version 2.0
 */

namespace Shopgate\ConnectSdk\Tests\Integration;

abstract class CustomerTest extends ShopgateSdkTest
{
    const CUSTOMER_SERVICE          = 'omni-customer';
    const CATALOG_SERVICE           = 'catalog';
    const METHOD_DELETE_ATTRIBUTE   = 'deleteAttribute';
    const METHOD_DELETE_CONTACT     = 'deleteContact';
    const METHOD_DELETE_CUSTOMER    = 'deleteCustomer';
    const METHOD_DELETE_WISHLIST    = 'deleteWishlist';
    const METHOD_DELETE_PRODUCT     = 'deleteProduct';
    const CONTACT_CODE              = 'integration-test';
    const WISHLIST_CODE             = 'integration-test-wishlist';
    const WISHLIST_PRODUCT_CODE     = 'wishlist-product-1';
    const WISHLIST_PRODUCT_CODE_TWO = 'wishlist-product-2';

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::CUSTOMER_SERVICE,
            $this->sdk->getCustomerService(),
            [
                self::METHOD_DELETE_CONTACT   => [],
                self::METHOD_DELETE_CUSTOMER  => [],
                self::METHOD_DELETE_ATTRIBUTE => [],
                self::METHOD_DELETE_WISHLIST  => []
            ]
        );

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_PRODUCT => []
            ]
        );
    }
}
