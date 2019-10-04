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

use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Customer\Contact;
use Shopgate\ConnectSdk\Exception;

abstract class CustomerTest extends ShopgateSdkTest
{
    const CUSTOMER_SERVICE          = 'customer';
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
    const SAMPLE_CATALOG_CODE       = 'NARetail';

    const CUSTOMER_CUSTOMER_EXTERNAL_CUSTOMER_CODE = 'external_customer_code';
    const CUSTOMER_CUSTOMER_FIRSTNAME = 'Firstname';
    const CUSTOMER_CUSTOMER_LASTNAME = 'Lastname';
    const CUSTOMER_CUSTOMER_MIDDLE_NAME = 'Middlename';
    const CUSTOMER_CUSTOMER_EMAIL = 'example+%s@mail.com';
    const CUSTOMER_SETTINGS_DEFAULT_LOCALE = 'en-us';
    const CUSTOMER_SETTINGS_DEFAULT_CURRENCY = 'USD';
    const CUSTOMER_SETTINGS_DEFAULT_COMMUNICATION_PREFERENCES = ['email', 'sms'];
    const CUSTOMER_SETTINGS_DEFAULT_LOCATION_CODE = 'DERetail001';
    const CUSTOMER_ATTRIBUTE_CODE = 'attribute_code';
    const CUSTOMER_ATTRIBUTE_NAME = 'Attribute name';
    const CUSTOMER_ATTRIBUTE_VALUE_CODE = 'attribute_value_code';
    const CUSTOMER_ATTRIBUTE_VALUE_NAME = 'Attribute Value Name';
    const CUSTOMER_CONTACT_EXTERNAL_CUSTOMER_CODE = 'customer_code';
    const CUSTOMER_CONTACT_FIRSTNAME = 'Firstname';
    const CUSTOMER_CONTACT_MIDDLE_NAME = 'Middlename';
    const CUSTOMER_CONTACT_LAST_NAME = 'Lastname';
    const CUSTOMER_CONTACT_COMPANY = 'Shopgate Inc';
    const CUSTOMER_CONTACT_ADDRESS_1 = 'Somestreet 12';
    const CUSTOMER_CONTACT_ADDRESS_2 = 'Address 2';
    const CUSTOMER_CONTACT_ADDRESS_3 = 'Address 3';
    const CUSTOMER_CONTACT_ADDRESS_4 = 'Address 4';
    const CUSTOMER_CONTACT_CITY = 'Austin';
    const CUSTOMER_CONTACT_POSTAL_CODE = '78732';
    const CUSTOMER_CONTACT_REGION = 'TX';
    const CUSTOMER_CONTACT_COUNTRY = 'US';
    const CUSTOMER_CONTACT_PHONE = '+1000000001';
    const CUSTOMER_CONTACT_FAX = '+1000000002';
    const CUSTOMER_CONTACT_MOBILE = '+1000000003';
    const CUSTOMER_CONTACT_EMAIL = 'somelocation+%s@someRetailer.com';

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
                self::METHOD_DELETE_WISHLIST  => [],
            ]
        );

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_PRODUCT => [],
            ]
        );
    }

    /**
     * @param int $itemCount
     *
     * @return Customer\Create[]
     *
     * @throws \Shopgate\ConnectSdk\Exception\Exception
     */
    public function provideSampleCustomers($itemCount = 2)
    {
        $result = [];
        for ($count = 1; $count < ($itemCount + 1); $count++) {
            $customer = new Customer\Create();
            $customer->setExternalCustomerNumber(self::CUSTOMER_CUSTOMER_EXTERNAL_CUSTOMER_CODE . $count);
            $customer->setFirstName(self::CUSTOMER_CUSTOMER_FIRSTNAME . $count);
            $customer->setMiddleName(self::CUSTOMER_CUSTOMER_MIDDLE_NAME . $count);
            $customer->setLastName(self::CUSTOMER_CUSTOMER_LASTNAME . $count);
            $customer->setEmailAddress(sprintf(self::CUSTOMER_CUSTOMER_EMAIL, $count));
            $customer->setStatus(Customer\Create::STATUS_ACTIVE);
            $customer->setIsAnonymous(false);

            $setting = new Customer\Dto\Settings();
            $setting->setDefaultLocale('en-us');
            $setting->setDefaultCurrency('USD');
            $setting->setCommunicationPreferences(['email']);
            $setting->setDefaultLocationCode('DERetail001');
            $setting->setMarketingOptIn(true);

            $customer->setSettings($setting);

            $result[] = $customer;
        }

        return $result;
    }

    /**
     * @return Customer\Create[]
     * @throws Exception\InvalidDataTypeException
     */
    public function provideSampleCustomers_()
    {
        return [
            $this->provideSampleCustomer('100'),
            $this->provideSampleCustomer('200'),
        ];
    }

    /**
     * @param string $externalCustomerNumber
     *
     * @return Customer\Create
     * @throws Exception\InvalidDataTypeException
     */
    public function provideSampleCustomer($externalCustomerNumber)
    {
        $customer = new Customer\Create();
        $customer->setFirstName('FirstName');
        $customer->setLastName('LastName');
        $customer->setEmailAddress('integration-test@shopgate.com');
        $customer->setExternalCustomerNumber($externalCustomerNumber);

        $contact = new Contact\Create();
        $contact->setFirstName('Firstname')
            ->setLastName('Lastname')
            ->setEmailAddress('test@shopgate.com')
            ->setExternalContactCode(self::CONTACT_CODE);

        $customer->setContacts([$contact]);

        return $customer;
    }
}
