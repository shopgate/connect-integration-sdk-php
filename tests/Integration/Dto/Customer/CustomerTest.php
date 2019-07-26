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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Customer;

use Shopgate\ConnectSdk\Dto\Customer\Attribute;
use Shopgate\ConnectSdk\Dto\Customer\AttributeValue;
use Shopgate\ConnectSdk\Dto\Customer\Contact;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Customer\Note\Create;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Tests\Integration\CustomerTest as CustomerBaseTest;

class CustomerTest extends CustomerBaseTest
{
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

    /**
     * @throws Exception
     */
    public function testGetListCustomersDirect()
    {
        // Arrange
        $createdItemCount = 10;
        $sampleCustomers = $this->provideSampleCustomers($createdItemCount);
        $response = $this->sdk->getCustomerService()->addCustomers($sampleCustomers);

        // Act
        $customers = $this->sdk->getCustomerService()->getCustomers()->getCustomers();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount($createdItemCount, $customers);
    }

    /**
     * @param int $itemCount
     *
     * @return Customer\Create[]
     */
    private function provideSampleCustomers($itemCount = 2)
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
     * @throws Exception
     */
    public function testMeta()
    {
        // Arrange
        $createdItemCount = 10;
        $sampleCustomers = $this->provideSampleCustomers($createdItemCount);
        $response = $this->sdk->getCustomerService()->addCustomers($sampleCustomers);

        // Act
        $meta = $this->sdk->getCustomerService()->getCustomers()->getMeta();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertEquals(10, $meta->getTotalItemCount());
    }

    /**
     * @throws Exception
     */
    public function testGetCustomersDirect()
    {
        // Arrange
        $createdItemCount = 10;
        $sampleCustomers = $this->provideSampleCustomers($createdItemCount);
        $response = $this->sdk->getCustomerService()->addCustomers($sampleCustomers);

        // Act
        $customer = $this->sdk->getCustomerService()->getCustomer($response['ids'][1]);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertEquals(
            self::CUSTOMER_CUSTOMER_EXTERNAL_CUSTOMER_CODE . '2',
            $customer->getExternalCustomerNumber()
        );
    }

    /**
     * @throws Exception
     */
    public function testCreateCustomersDirect()
    {
        // Arrange
        $createdItemCount = 10;
        $sampleCustomers = $this->provideSampleCustomers($createdItemCount);

        // Act
        $response = $this->sdk->getCustomerService()->addCustomers($sampleCustomers);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );

        // Assert
        $customers = $this->sdk->getCustomerService()->getCustomers();
        /** @noinspection PhpParamsInspection */
        $this->assertCount($createdItemCount, $customers->getCustomers());
    }

    /**
     * @throws Exception
     */
    public function testCreateCustomersFullDirect()
    {
        // Arrange
        $customer = new Customer\Create();
        $customer->setExternalCustomerNumber(self::CUSTOMER_CUSTOMER_EXTERNAL_CUSTOMER_CODE);
        $customer->setFirstName(self::CUSTOMER_CUSTOMER_FIRSTNAME);
        $customer->setMiddleName(self::CUSTOMER_CUSTOMER_MIDDLE_NAME);
        $customer->setLastName(self::CUSTOMER_CUSTOMER_LASTNAME);
        $customer->setEmailAddress(sprintf(self::CUSTOMER_CUSTOMER_EMAIL, 1));
        $customer->setStatus(Customer\Create::STATUS_ACTIVE);
        $customer->setIsAnonymous(false);

        $setting = new Customer\Dto\Settings();
        $setting->setDefaultLocale(self::CUSTOMER_SETTINGS_DEFAULT_LOCALE);
        $setting->setDefaultCurrency(self::CUSTOMER_SETTINGS_DEFAULT_CURRENCY);
        $setting->setCommunicationPreferences(self::CUSTOMER_SETTINGS_DEFAULT_COMMUNICATION_PREFERENCES);
        $setting->setDefaultLocationCode(self::CUSTOMER_SETTINGS_DEFAULT_LOCATION_CODE);
        $setting->setMarketingOptIn(true);

        $customer->setSettings($setting);

        // Create attribute
        $createAttribute = new Attribute\Create();
        $createAttribute->setCode(self::CUSTOMER_ATTRIBUTE_CODE)
            ->setType(Attribute\Create::TYPE_TEXT)
            ->setIsRequired(true)
            ->setName(self::CUSTOMER_ATTRIBUTE_NAME);

        // Create attribute value
        $createAttributeValue = new AttributeValue\Create();
        $createAttributeValue->setCode(self::CUSTOMER_ATTRIBUTE_VALUE_CODE);
        $createAttributeValue->setSequenceId(0);
        $createAttributeValue->setName(self::CUSTOMER_ATTRIBUTE_VALUE_NAME);

        $createAttribute->setValues([$createAttributeValue]);

        $this->sdk->getCustomerService()->addAttributes([$createAttribute]);

        $attribute = new Customer\Dto\Attribute();
        $attribute->setCode(self::CUSTOMER_ATTRIBUTE_CODE);
        $attribute->setName('Attribute Name');
        $value = new Customer\Dto\Attribute\Value();
        $value->setCode(self::CUSTOMER_ATTRIBUTE_VALUE_CODE);
        $attribute->setValue($value);

        // Add attribute
        $customer->setAttributes([$attribute]);

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

        // Act
        $response = $this->sdk->getCustomerService()->addCustomers([$customer]);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            [self::CUSTOMER_ATTRIBUTE_CODE]
        );

        // Assert
        $customer = $this->sdk->getCustomerService()->getCustomer($response['ids'][0]);
        $settings = $customer->getSettings();
        $contacts = $customer->getContacts();
        $attributes = $customer->getAttributes();

        /** @noinspection PhpParamsInspection */
        // General
        $this->assertEquals(Customer\Create::STATUS_ACTIVE, $customer->getStatus());
        $this->assertEquals(self::CUSTOMER_CUSTOMER_FIRSTNAME, $customer->getFirstName());
        $this->assertEquals(self::CUSTOMER_CUSTOMER_LASTNAME, $customer->getLastName());
        $this->assertEquals(self::CUSTOMER_CUSTOMER_MIDDLE_NAME, $customer->getMiddleName());
        $this->assertEquals(sprintf(self::CUSTOMER_CUSTOMER_EMAIL, 1), $customer->getEmailAddress());
        $this->assertEquals(false, $customer->getIsAnonymous());

        // Settings
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_LOCALE, $settings->getDefaultLocale());
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_CURRENCY, $settings->getDefaultCurrency());
        $this->assertEquals(self::CUSTOMER_SETTINGS_DEFAULT_LOCATION_CODE, $settings->getDefaultLocationCode());
        $this->assertCount(2, $settings->getCommunicationPreferences());

        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertContains('email', $settings->getCommunicationPreferences()->toArray());
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertContains('sms', $settings->getCommunicationPreferences()->toArray());
        $this->assertEquals(true, $settings->getMarketingOptIn());

        //Contacts
        $this->assertCount(1, $contacts);
        $contact = $contacts[0];
        $this->assertEquals(self::CUSTOMER_CONTACT_EXTERNAL_CUSTOMER_CODE, $contact->getExternalContactCode());
        $this->assertEquals(self::CUSTOMER_CONTACT_FIRSTNAME, $contact->getFirstName());
        $this->assertEquals(self::CUSTOMER_CONTACT_LAST_NAME, $contact->getLastName());
        $this->assertEquals(self::CUSTOMER_CONTACT_MIDDLE_NAME, $contact->getMiddleName());
        $this->assertEquals(self::CUSTOMER_CONTACT_COMPANY, $contact->getCompanyName());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_1, $contact->getAddress1());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_2, $contact->getAddress2());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_3, $contact->getAddress3());
        $this->assertEquals(self::CUSTOMER_CONTACT_ADDRESS_4, $contact->getAddress4());
        $this->assertEquals(self::CUSTOMER_CONTACT_CITY, $contact->getCity());
        $this->assertEquals(self::CUSTOMER_CONTACT_REGION, $contact->getRegion());
        $this->assertEquals(self::CUSTOMER_CONTACT_POSTAL_CODE, $contact->getPostalCode());
        $this->assertEquals(self::CUSTOMER_CONTACT_COUNTRY, $contact->getCountry());
        $this->assertEquals(self::CUSTOMER_CONTACT_PHONE, $contact->getPhone());
        $this->assertEquals(self::CUSTOMER_CONTACT_MOBILE, $contact->getMobile());
        $this->assertEquals(self::CUSTOMER_CONTACT_FAX, $contact->getFax());
        $this->assertEquals(sprintf(self::CUSTOMER_CONTACT_EMAIL, 1), $contact->getEmailAddress());
        $this->assertEquals(true, $contact->getIsDefaultBilling());
        $this->assertEquals(true, $contact->getIsDefaultShipping());

        //Attributes
        $this->assertCount(1, $attributes);
        $attribute = $attributes[0];

        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_NAME, $attribute->getName());
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_CODE, $attribute->getCode());

        //Attribute value
        $value = $attribute->getValue();
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_VALUE_NAME, $value->getName());
        $this->assertEquals(self::CUSTOMER_ATTRIBUTE_VALUE_CODE, $value->getCode());
    }

    /**
     * @throws Exception
     */
    public function testDeleteCustomerDirect()
    {
        // Arrange
        $createdItemCount = 1;
        $sampleCustomers = $this->provideSampleCustomers($createdItemCount);

        $response = $this->sdk->getCustomerService()->addCustomers($sampleCustomers);

        // Act
        $this->sdk->getCustomerService()->deleteCustomer($response['ids'][0]);

        // Assert
        try {
            $this->sdk->getCustomerService()->getCustomer($response['ids'][0]);
        } catch (NotFoundException $exception) {
            $this->assertEquals($exception->getMessage(), '{"code":"NotFound","message":"Customer not found"}');
        }
    }

    /**
     * @throws Exception
     */
    public function testUpdateCustomerDirect()
    {
        // Arrange
        $createdItemCount = 1;
        $sampleCustomers = $this->provideSampleCustomers($createdItemCount);

        $response = $this->sdk->getCustomerService()->addCustomers($sampleCustomers);

        $customerUpdate = new Customer\Update();
        $customerUpdate->setFirstName(self::CUSTOMER_CUSTOMER_FIRSTNAME . ' Update');
        $customerUpdate->setMiddleName(self::CUSTOMER_CUSTOMER_MIDDLE_NAME . ' Update');
        $customerUpdate->setLastName(self::CUSTOMER_CUSTOMER_LASTNAME . ' Update');
        $customerUpdate->setEmailAddress(sprintf(self::CUSTOMER_CUSTOMER_EMAIL, 'update'));
        $customerUpdate->setStatus(Customer\Create::STATUS_INACTIVE);
        $customerUpdate->setIsAnonymous(true);

        $customerSettingsUpdate = new Customer\Dto\Settings();
        $customerSettingsUpdate->setMarketingOptIn(false);
        $customerSettingsUpdate->setDefaultLocale('de-de');
        $customerSettingsUpdate->setDefaultLocationCode('EUR');
        $customerSettingsUpdate->setCommunicationPreferences(['sms', 'email']);

        $customerUpdate->setSettings($customerSettingsUpdate);

        // Act
        $this->sdk->getCustomerService()->updateCustomer(
            $response['ids'][0],
            $customerUpdate
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );

        // Assert
        $customer = $this->sdk->getCustomerService()->getCustomer($response['ids'][0]);
        $settings = $customer->getSettings();

        // General
        $this->assertEquals(Customer\Create::STATUS_INACTIVE, $customer->getStatus());
        $this->assertEquals(self::CUSTOMER_CUSTOMER_FIRSTNAME . ' Update', $customer->getFirstName());
        $this->assertEquals(self::CUSTOMER_CUSTOMER_LASTNAME . ' Update', $customer->getLastName());
        $this->assertEquals(self::CUSTOMER_CUSTOMER_MIDDLE_NAME . ' Update', $customer->getMiddleName());
        $this->assertEquals(sprintf(self::CUSTOMER_CUSTOMER_EMAIL, 'update'), $customer->getEmailAddress());
        $this->assertEquals(true, $customer->getIsAnonymous());

        // Settings
        $this->assertEquals('de-de', $settings->getDefaultLocale());
        $this->assertEquals('USD', $settings->getDefaultCurrency());
        $this->assertEquals('EUR', $settings->getDefaultLocationCode());
        $this->assertCount(2, $settings->getCommunicationPreferences());
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertContains('sms', $settings->getCommunicationPreferences()->toArray());
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertContains('email', $settings->getCommunicationPreferences()->toArray());
        $this->assertEquals(false, $settings->getMarketingOptIn());
    }

    /**
     * @param array            $customerData
     * @param RequestException $expectedException
     * @param string           $missingItem
     *
     * @throws Exception
     *
     * @dataProvider provideCreateCustomerWithMissingRequiredFields
     */
    public function testCreateCustomerDirectWithMissingRequiredFields(
        array $customerData,
        $expectedException,
        $missingItem
    ) {
        // Arrange
        $customer = new Customer\Create($customerData);

        // Act
        try {
            $this->sdk->getCustomerService()->addCustomers([$customer]);
        } catch (RequestException $exception) {
            // Assert
            $errors = \GuzzleHttp\json_decode($exception->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals('Missing required property: ' . $missingItem, $message);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @return array
     */
    public function provideCreateCustomerWithMissingRequiredFields()
    {
        return [
            'missing firstName' => [
                'customerData' => [
                    'emailAddress' => 'example@shopgate.com',
                    'lastName' => 'Doe',
                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'firstName',
            ],
            'missing emailAddress' => [
                'customerData' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'emailAddress',
            ],
            'missing lastName' => [
                'customerData' => [
                    'firstName' => 'John',
                    'emailAddress' => 'example@shopgate.com',
                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'lastName',
            ],
        ];
    }

    /**
     * @throws NotFoundException
     * @throws RequestException
     * @throws AuthenticationInvalidException
     * @throws UnknownException
     */
    public function testNoteCreationAndRetrieval()
    {
        // Arrange
        $firstNote = (new Create())
            ->setNote('First Note')
            ->setExternalCode('firstNote')
            ->setDate('2019-06-21T12:17:33.000Z')
            ->setCreator('Konstantin');
        $secondNote = (new Create())
            ->setNote('Second Note')
            ->setExternalCode('secondNote')
            ->setDate('2019-06-13T12:17:33.000Z')
            ->setCreator('Other Creator');
        $sampleCustomer = $this->provideSampleCustomers(1);
        $customers = $this->sdk->getCustomerService()->addCustomers($sampleCustomer);
        $this->assertArrayHasKey('ids', $customers);

        // Clean Up Customers
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $customers['ids']
        );

        // Act
        $noteResponse = $this->sdk->getCustomerService()->addNotes($customers['ids'][0], [$firstNote, $secondNote]);
        $this->assertNotEmpty($noteResponse);

        $noteList = $this->sdk->getCustomerService()->getNotes($customers['ids'][0]);

        $this->assertEquals(2, $noteList->getMeta()->getTotalItemCount());

        $notes = $noteList->getNotes();
        $this->assertEquals('First Note', $notes[0]->getNote());
        $this->assertEquals('firstNote', $notes[0]->getExternalCode());
        $this->assertEquals('2019-06-21T12:17:33.000Z', $notes[0]->getDate());
        $this->assertEquals('Konstantin', $notes[0]->getCreator());
        $this->assertEquals('Second Note', $notes[1]->getNote());
        $this->assertEquals('secondNote', $notes[1]->getExternalCode());
        $this->assertEquals('2019-06-13T12:17:33.000Z', $notes[1]->getDate());
        $this->assertEquals('Other Creator', $notes[1]->getCreator());
    }
}
