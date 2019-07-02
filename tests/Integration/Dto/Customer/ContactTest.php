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

use Shopgate\ConnectSdk\Dto\Customer\Contact as ContactDto;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Exception;
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Tests\Integration\CustomerTest;

class ContactTest extends CustomerTest
{
    /**
     * @param ContactDto\Create $sampleContacts
     *
     * @dataProvider providerCreateContactDirect
     */
    public function testCreateContactDirect($sampleContacts)
    {
        // Arrange
        $customerId = $this->createCustomer();

        // Act
        $this->sdk->getCustomerService()->addContacts($customerId, $sampleContacts);

        // Assert
        $customer = $this->sdk->getCustomerService()->getCustomer($customerId);
        /** @noinspection PhpParamsInspection */
        $this->assertCount(count($sampleContacts), $customer->getContacts());

        // CleanUp
        $deleteIds = [];

        foreach ($customer->getContacts() as $contact) {
            $deleteIds[] = [$contact->getId(), $customerId];
        }

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CONTACT,
            $deleteIds
        );

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            [$customerId]
        );
    }

    /**
     * @param array               $fieldset
     * @param Exception\Exception $expectedException
     * @param string              $missingField
     *
     * @dataProvider providerForMissingRequiredFields
     */
    public function testMissingRequiredFields($fieldset, $expectedException, $missingField)
    {
        // Arrange
        $customerId = $this->createCustomer();
        $contact = new ContactDto\Create($fieldset);

        // Act
        try {
            $this->sdk->getCustomerService()->addContacts($customerId, [$contact]);

            $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
        } catch (Exception\Exception $exception) {
            // Assert
            $errors  = \GuzzleHttp\json_decode($exception->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals('Missing required property: ' . $missingField, $message);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());
        }

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            [$customerId]
        );
    }

    /**
     * @param bool $withContact
     *
     * @return string customer id
     *
     * @throws Exception\AuthenticationInvalidException
     * @throws Exception\NotFoundException
     * @throws Exception\RequestException
     * @throws Exception\UnknownException
     */
    private function createCustomer($withContact = false)
    {
        $customer = new Customer\Create();
        $customer->setFirstName('FirstName');
        $customer->setLastName('LastName');
        $customer->setEmailAddress('integration-test@shopgate.com');

        if ($withContact) {
            $contact = new ContactDto\Create();
            $contact->setFirstName('Firstname')
                ->setLastName('Lastname')
                ->setEmailAddress('test@shopgate.com')
                ->setExternalContactCode(self::CONTACT_CODE);
            $customer->setContacts([$contact]);
        }

        $response = $this->sdk->getCustomerService()->addCustomers(
            [$customer], ['requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT]
        );

        $id = array_pop($response['ids']);

        return $id;
    }

    /**
     * Tests updating a contact
     */
    public function testUpdateContact()
    {
        // Arrange
        $customerId       = $this->createCustomer(true);
        $customer         = $this->sdk->getCustomerService()->getCustomer($customerId);
        $contact          = $customer->getContacts()[0];
        $updatedFirstName = 'TestName';
        $updatedCity      = 'Magdeburg';

        // Act
        $updateDto = new ContactDto\Update();
        $updateDto->setFirstName($updatedFirstName)
            ->setCity($updatedCity);

        $this->sdk->getCustomerService()->updateContact($contact->getId(), $customerId, $updateDto);

        // Assert
        $this->assertNotEquals($contact->getFirstName(), $updatedFirstName);
        $this->assertNotEquals($contact->getCity(), $updatedCity);

        $customer       = $this->sdk->getCustomerService()->getCustomer($customerId);
        $updatedContact = $customer->getContacts()[0];

        $this->assertEquals($updatedFirstName, $updatedContact->getFirstName());
        $this->assertEquals($updatedCity, $updatedContact->getCity());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CONTACT,
            [[$contact->getId(), $customerId]]
        );

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            [$customerId]
        );
    }

    /**
     * Test deleting a contact
     */
    public function testDeleteContact()
    {
        // Arrange
        $customerId = $this->createCustomer(true);
        $customer   = $this->sdk->getCustomerService()->getCustomer($customerId);
        $contact    = $customer->getContacts()[0];

        // Act
        $this->sdk->getCustomerService()->deleteContact($contact->getId(), $customerId);
        $updatedCustomer = $this->sdk->getCustomerService()->getCustomer($customerId);

        // Assert
        $this->assertCount(1, $customer->getContacts());
        $this->assertCount(0, $updatedCustomer->getContacts());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            [$customerId]
        );
    }

    /**
     * @return array
     */
    public function providerCreateContactDirect()
    {
        return [
            'Single contact, full example'    => [$this->createSampleContacts(1)],
            'Multiple contacts, full example' => [$this->createSampleContacts(2)],
        ];
    }

    /**
     * @return array
     */
    public function providerForMissingRequiredFields()
    {
        return [
            'missing firstName'       => [
                [
                    'lastName' => 'LastName'
                ],
                new Exception\RequestException(400),
                'firstName',
            ],
            'missing lastName' => [
                [
                    'firstName' => 'FirstName'
                ],
                new Exception\RequestException(400),
                'lastName',
            ]
        ];
    }

    /**
     * @param int $amount
     *
     * @return ContactDto\Create[]
     */
    private function createSampleContacts($amount)
    {
        $contacts = [];
        for ($i = 1; $i <= $amount; $i++) {
            $contact = new ContactDto\Create();
            $contact->setExternalContactCode(self::CONTACT_CODE . '-' . $i)
                ->setStatus(ContactDto::STATUS_ACTIVE)
                ->setFirstName('Firstname')
                ->setLastName('Lastname')
                ->setMiddleName('Middlename')
                ->setCompanyName('Shopgate')
                ->setAddress1('Address 1')
                ->setAddress2('Address 2')
                ->setAddress3('Address 3')
                ->setAddress4('Address 4')
                ->setCity('Butzbach')
                ->setPostalCode('35510')
                ->setRegion('SA')
                ->setCountry('DE')
                ->setPhone('+49123456789')
                ->setFax('+49987654321')
                ->setMobile('+49123123123')
                ->setEmailAddress('integration+' . $i . '@shopgate.com');

            $contacts[] = $contact;
        }

        return $contacts;
    }
}
