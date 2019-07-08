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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Customer;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Customer\Customer\Dto\Attribute;
use Shopgate\ConnectSdk\Dto\Customer\Customer\Dto\Settings;
use Shopgate\ConnectSdk\Dto\Customer\Customer\Get;

class GetTest extends TestCase
{
    const CUSTOMER_ID                                         = 'd162439c-e2f9-4050-8b94-6bff33e8163b';
    const CUSTOMER_CREATE_DATE                                = '2019-04-22T20:16:23.000Z';
    const CUSTOMER_EXTERNAL_CUSTOMER_NUMBER                   = '10001';
    const CUSTOMER_FIRST_NAME                                 = 'John';
    const CUSTOMER_MIDDLE_NAME                                = 'Doug';
    const CUSTOMER_LAST_NAME                                  = 'Doe';
    const CUSTOMER_EMAIL_ADDRESS                              = 'john.doe@shopgate.com';
    const CUSTOMER_ATTRIBUTE_CODE_1                           = 'tshirtSize';
    const CUSTOMER_ATTRIBUTE_NAME_1                           = 'T-Shirt Size';
    const CUSTOMER_ATTRIBUTE_CODE_2                           = 'nameOfCat';
    const CUSTOMER_ATTRIBUTE_NAME_2                           = 'Name of your cat';
    const CUSTOMER_ATTRIBUTE_VALUE_CODE_1                     = 'l';
    const CUSTOMER_ATTRIBUTE_VALUE_NAME_1                     = 'L';
    const CUSTOMER_ATTRIBUTE_VALUE_2                          = 'Some Cat Name';
    const CUSTOMER_SETTINGS_DEFAULT_LOCALE                    = 'en-us';
    const CUSTOMER_SETTINGS_DEFAULT_CURRENCY                  = 'USD';
    const CUSTOMER_SETTINGS_DEFAULT_COMMUNICATION_PREFERENCES = ['email', 'sms'];
    const CUSTOMER_SETTINGS_DEFAULT_LOCATION_CODE             = 'DERetail001';
    const CUSTOMER_CONTACT_ID                                 = 'c162439c-e2f9-4050-8b94-6bff33e8163b';
    const CUSTOMER_CONTACT_EXTERNAL_CUSTOMER_CODE             = 'customer_code';
    const CUSTOMER_CONTACT_FIRSTNAME                          = 'Firstname';
    const CUSTOMER_CONTACT_MIDDLE_NAME                        = 'Middlename';
    const CUSTOMER_CONTACT_LAST_NAME                          = 'Lastname';
    const CUSTOMER_CONTACT_COMPANY                            = 'Shopgate Inc';
    const CUSTOMER_CONTACT_ADDRESS_1                          = 'Somestreet 12';
    const CUSTOMER_CONTACT_ADDRESS_2                          = 'Address 2';
    const CUSTOMER_CONTACT_ADDRESS_3                          = 'Address 3';
    const CUSTOMER_CONTACT_ADDRESS_4                          = 'Address 4';
    const CUSTOMER_CONTACT_CITY                               = 'Austin';
    const CUSTOMER_CONTACT_POSTAL_CODE                        = '78732';
    const CUSTOMER_CONTACT_REGION                             = 'TX';
    const CUSTOMER_CONTACT_COUNTRY                            = 'US';
    const CUSTOMER_CONTACT_PHONE                              = '+1000000001';
    const CUSTOMER_CONTACT_FAX                                = '+1000000002';
    const CUSTOMER_CONTACT_MOBILE                             = '+1000000003';
    const CUSTOMER_CONTACT_EMAIL                              = 'somelocation+%s@someRetailer.com';

    /**
     * Tests minimal DTO structure return
     */
    public function testBasicProperties()
    {
        $get        = new Get($this->getValidEntry());
        $contacts   = $get->getContacts();
        $attributes = $get->getAttributes();
        $settings   = $get->getSettings();

        // Test sub DTOs
        $this->assertInstanceOf(Customer\Dto\Contact::class, $contacts);
        $this->assertInstanceOf(Attribute::class, $attributes);
        $this->assertInstanceOf(Settings::class, $settings);

        // Test basic
        $this->assertEquals(self::CUSTOMER_EXTERNAL_CUSTOMER_NUMBER, $get->getExternalCustomerNumber());
        $this->assertEquals(self::CUSTOMER_FIRST_NAME, $get->getFirstName());
        $this->assertEquals(self::CUSTOMER_MIDDLE_NAME, $get->getMiddleName());
        $this->assertEquals(self::CUSTOMER_LAST_NAME, $get->getLastName());
        $this->assertEquals(self::CUSTOMER_EMAIL_ADDRESS, $get->getEmailAddress());
        $this->assertEquals(Customer::STATUS_ACTIVE, $get->getStatus());
        $this->assertEquals(false, $get->getIsAnonymous());
    }

    /**
     * @return array
     */
    protected function getValidEntry()
    {
        return [
            'id'                     => GetTest::CUSTOMER_ID,
            'createDate'             => GetTest::CUSTOMER_CREATE_DATE,
            'externalCustomerNumber' => GetTest::CUSTOMER_EXTERNAL_CUSTOMER_NUMBER,
            'firstName'              => GetTest::CUSTOMER_FIRST_NAME,
            'middleName'             => GetTest::CUSTOMER_MIDDLE_NAME,
            'lastName'               => GetTest::CUSTOMER_LAST_NAME,
            'emailAddress'           => GetTest::CUSTOMER_EMAIL_ADDRESS,
            'status'                 => Customer::STATUS_ACTIVE,
            'isAnonymous'            => false,
            'contacts'               => [
                [
                    'id'                  => self::CUSTOMER_CONTACT_ID,
                    'externalContactCode' => self::CUSTOMER_CONTACT_EXTERNAL_CUSTOMER_CODE,
                    'status'              => Customer::STATUS_ACTIVE,
                    'firstName'           => self::CUSTOMER_CONTACT_FIRSTNAME,
                    'middleName'          => self::CUSTOMER_CONTACT_MIDDLE_NAME,
                    'lastName'            => self::CUSTOMER_CONTACT_LAST_NAME,
                    'companyName'         => self::CUSTOMER_CONTACT_COMPANY,
                    'address1'            => self::CUSTOMER_CONTACT_ADDRESS_1,
                    'address2'            => self::CUSTOMER_CONTACT_ADDRESS_2,
                    'address3'            => self::CUSTOMER_CONTACT_ADDRESS_3,
                    'address4'            => self::CUSTOMER_CONTACT_ADDRESS_4,
                    'city'                => self::CUSTOMER_CONTACT_CITY,
                    'postalCode'          => self::CUSTOMER_CONTACT_POSTAL_CODE,
                    'region'              => self::CUSTOMER_CONTACT_REGION,
                    'country'             => self::CUSTOMER_CONTACT_COUNTRY,
                    'phone'               => self::CUSTOMER_CONTACT_PHONE,
                    'fax'                 => self::CUSTOMER_CONTACT_FAX,
                    'mobile'              => self::CUSTOMER_CONTACT_MOBILE,
                    'emailAddress'        => sprintf(self::CUSTOMER_CONTACT_EMAIL, 1),
                    'isDefaultBilling'    => true,
                    'isDefaultShipping'   => true,
                ],
            ],
            'attributes'             => [
                [
                    'code'  => self::CUSTOMER_ATTRIBUTE_CODE_1,
                    'name'  => self::CUSTOMER_ATTRIBUTE_NAME_1,
                    'value' => [
                        'code' => self::CUSTOMER_ATTRIBUTE_VALUE_CODE_1,
                        'name' => self::CUSTOMER_ATTRIBUTE_VALUE_NAME_1,
                    ],

                ],
                [
                    'code'  => self::CUSTOMER_ATTRIBUTE_CODE_2,
                    'name'  => self::CUSTOMER_ATTRIBUTE_NAME_2,
                    'value' => self::CUSTOMER_ATTRIBUTE_VALUE_2,
                ],
            ],
            'settings'               => [
                'defaultLocale'            => self::CUSTOMER_SETTINGS_DEFAULT_LOCALE,
                'defaultCurrency'          => self::CUSTOMER_SETTINGS_DEFAULT_CURRENCY,
                'communicationPreferences' => self::CUSTOMER_SETTINGS_DEFAULT_COMMUNICATION_PREFERENCES,
                'defaultLocationCode'      => self::CUSTOMER_SETTINGS_DEFAULT_LOCATION_CODE,
                'marketingOptIn'           => true,

            ],
        ];
    }

    /**
     * Tests contacts DTO structure return
     */
    public function testGetContacts()
    {
        $get      = new Get($this->getValidEntry());
        $contacts = $get->getContacts();
        $contact  = $contacts[0];

        // Global
        $this->assertCount(1, $contacts);

        // Contact
        $this->assertInstanceOf(Customer\Dto\Contact::class, $contact);
        $this->assertEquals(self::CUSTOMER_CONTACT_ID, $contact->getId());
        $this->assertEquals(self::CUSTOMER_CONTACT_ID, $contact->getId());
        $this->assertEquals(self::CUSTOMER_CONTACT_EXTERNAL_CUSTOMER_CODE, $contact->getExternalContactCode());
        $this->assertEquals(Customer::STATUS_ACTIVE, $contact->getStatus());
        $this->assertEquals(self::CUSTOMER_CONTACT_FIRSTNAME, $contact->getFirstName());
        $this->assertEquals(self::CUSTOMER_CONTACT_LAST_NAME, $contact->getLastName());
        $this->assertEquals(self::CUSTOMER_CONTACT_MIDDLE_NAME, $contact->getMiddleName());
        $this->assertEquals(self::CUSTOMER_CONTACT_COMPANY, $contact->getCompanyName());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_1, $contact->getAddress1());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_2, $contact->getAddress2());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_3, $contact->getAddress3());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_4, $contact->getAddress4());
        $this->assertEquals(self::CUSTOMER_CONTACT_CITY, $contact->getCity());
        $this->assertEquals(self::CUSTOMER_CONTACT_POSTAL_CODE, $contact->getPostalCode());
        $this->assertEquals(self::CUSTOMER_CONTACT_REGION, $contact->getRegion());
        $this->assertEquals(self::CUSTOMER_CONTACT_COUNTRY, $contact->getCountry());
        $this->assertEquals(self::CUSTOMER_CONTACT_PHONE, $contact->getPhone());
        $this->assertEquals(self::CUSTOMER_CONTACT_FAX, $contact->getFax());
        $this->assertEquals(self::CUSTOMER_CONTACT_MOBILE, $contact->getMobile());
        $this->assertEquals(sprintf(self::CUSTOMER_CONTACT_EMAIL, 1), $contact->getEmailAddress());
        $this->assertEquals(true, $contact->getIsDefaultBilling());
        $this->assertEquals(true, $contact->getIsDefaultShipping());
    }

    /**
     * Tests attributes DTO structure return
     */
    public function testGetAttributes()
    {
        $get = new Get($this->getValidEntry());

        $attributes = $get->getAttributes();
        $attribute1 = $attributes[0];
        $value1     = $attribute1->getValue();
        $attribute2 = $attributes[1];
        $value2     = $attribute1->getValue();

        // Global
        $this->assertCount(2, $attributes);

        // 1nd attribute
        $this->assertInstanceOf(Customer\Dto\Attribute::class, $attribute1);
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_CODE_1, $attribute1->getCode());
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_NAME_1, $attribute1->getName());
        $this->assertInstanceOf(Attribute\Value::class, $value1);
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_VALUE_CODE_1, $value1->getCode());
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_VALUE_NAME_1, $value1->getName());
        // 2nd attribute
        $this->assertInstanceOf(Customer\Dto\Attribute::class, $attribute2);
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_CODE_2, $attribute2->getCode());
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_NAME_2, $attribute2->getName());
        $this->assertInstanceOf(Attribute\Value::class, $value2);
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_VALUE_2, $value2);
    }

    /**
     * Tests settings DTO structure return
     */
    public function testGetSettings()
    {
        $get      = new Get($this->getValidEntry());
        $settings = $get->getSettings();

        $this->assertInstanceOf(Customer\Dto\Settings::class, $settings);
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_LOCALE, $settings->getDefaultLocale());
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_CURRENCY, $settings->getDefaultCurrency());
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_COMMUNICATION_PREFERENCES,
            $settings->getCommunicationPreferences()->toArray());
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_LOCATION_CODE, $settings->getDefaultLocationCode());
        $this->assertEquals(true, $settings->getMarketingOptIn());
    }
}
