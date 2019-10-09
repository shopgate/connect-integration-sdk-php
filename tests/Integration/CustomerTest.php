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

use Shopgate\ConnectSdk\Dto\Customer\Contact;
use Shopgate\ConnectSdk\Dto\Customer\Customer;

abstract class CustomerTest extends ShopgateSdkTest
{
    const CONTACT_CODE              = 'integration-test';
    const WISHLIST_CODE             = 'integration-test-wishlist';
    const WISHLIST_PRODUCT_CODE     = 'wishlist-product-1';
    const WISHLIST_PRODUCT_CODE_TWO = 'wishlist-product-2';

    const CUSTOMER_EXTERNAL_CUSTOMER_CODE = 'external_customer_code';
    const CUSTOMER_FIRSTNAME = 'Firstname';
    const CUSTOMER_LASTNAME = 'Lastname';
    const CUSTOMER_MIDDLE_NAME = 'Middlename';
    const CUSTOMER_EMAIL = 'example+%s@mail.com';
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
            $customer->setExternalCustomerNumber(self::CUSTOMER_EXTERNAL_CUSTOMER_CODE . $count);
            $customer->setFirstName(self::CUSTOMER_FIRSTNAME . $count);
            $customer->setMiddleName(self::CUSTOMER_MIDDLE_NAME . $count);
            $customer->setLastName(self::CUSTOMER_LASTNAME . $count);
            $customer->setEmailAddress(sprintf(self::CUSTOMER_EMAIL, $count));
            $customer->setStatus(Customer\Create::STATUS_ACTIVE);
            $customer->setIsAnonymous(false);

            $setting = new Customer\Dto\Settings();
            $setting->setDefaultLocale('en-us');
            $setting->setDefaultCurrency('USD');
            $setting->setCommunicationPreferences(['email']);
            $setting->setDefaultLocationCode('DERetail001');
            $setting->setMarketingOptIn(true);

            $customer->setSettings($setting);

            // Contact
            $contact = new Contact\Create();
            $contact->setExternalContactCode(self::CUSTOMER_CONTACT_EXTERNAL_CUSTOMER_CODE);
            $contact->setStatus(Contact\Create::STATUS_ACTIVE);
            $contact->setFirstName(self::CUSTOMER_CONTACT_FIRSTNAME);
            $contact->setMiddleName(self::CUSTOMER_CONTACT_MIDDLE_NAME);
            $contact->setLastName(self::CUSTOMER_CONTACT_LAST_NAME);
            $contact->setCompanyName(self::CUSTOMER_CONTACT_COMPANY);
            $contact->setAddress1(self::CUSTOMER_CONTACT_ADDRESS_1);
            $contact->setAddress2(self::CUSTOMER_CONTACT_ADDRESS_2);
            $contact->setAddress3(self::CUSTOMER_CONTACT_ADDRESS_3);
            $contact->setAddress4(self::CUSTOMER_CONTACT_ADDRESS_4);
            $contact->setCity(self::CUSTOMER_CONTACT_CITY);
            $contact->setPostalCode(self::CUSTOMER_CONTACT_POSTAL_CODE);
            $contact->setRegion(self::CUSTOMER_CONTACT_REGION);
            $contact->setCountry(self::CUSTOMER_CONTACT_COUNTRY);
            $contact->setPhone(self::CUSTOMER_CONTACT_PHONE);
            $contact->setFax(self::CUSTOMER_CONTACT_FAX);
            $contact->setMobile(self::CUSTOMER_CONTACT_MOBILE);
            $contact->setEmailAddress(sprintf(self::CUSTOMER_CONTACT_EMAIL, 1));
            $contact->setIsDefaultShipping(true);
            $contact->setIsDefaultBilling(true);

            $customer->setContacts([$contact]);

            $result[] = $customer;
        }

        return $result;
    }
}
