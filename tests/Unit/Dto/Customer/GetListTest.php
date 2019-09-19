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
use Shopgate\ConnectSdk\Dto\Customer\Customer\Get;
use Shopgate\ConnectSdk\Dto\Customer\Customer\GetList;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\Exception;

class GetListTest extends TestCase
{
    /**
     * Tests basic DTO structure return
     *
     * @throws Exception
     */
    public function testCustomerDto()
    {
        $entry = [
            'meta' => [
                'limit' => 1,
            ],
            'customers' => [
                [
                    'id' => GetTest::CUSTOMER_ID,
                    'createDate' => GetTest::CUSTOMER_CREATE_DATE,
                    'externalCustomerNumber' => GetTest::CUSTOMER_EXTERNAL_CUSTOMER_NUMBER,
                    'firstName' => GetTest::CUSTOMER_FIRST_NAME,
                    'middleName' => GetTest::CUSTOMER_MIDDLE_NAME,
                    'lastName' => GetTest::CUSTOMER_LAST_NAME,
                    'emailAddress' => GetTest::CUSTOMER_EMAIL_ADDRESS,
                    'status' => Customer::STATUS_ACTIVE,
                    'isAnonymous' => false,
                ],
            ],
        ];
        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(1, $getList->getMeta()->getLimit());

        $customers = $getList->getCustomers();
        /** @var Get $customer */
        $customer = $customers[0];
        $this->assertTrue(is_array($customers));
        $this->assertInstanceOf(Get::class, $customer);

        $this->assertEquals(GetTest::CUSTOMER_EXTERNAL_CUSTOMER_NUMBER, $customer->getExternalCustomerNumber());
        $this->assertEquals(GetTest::CUSTOMER_FIRST_NAME, $customer->getFirstName());
        $this->assertEquals(GetTest::CUSTOMER_MIDDLE_NAME, $customer->getMiddleName());
        $this->assertEquals(GetTest::CUSTOMER_LAST_NAME, $customer->getLastName());
        $this->assertEquals(GetTest::CUSTOMER_EMAIL_ADDRESS, $customer->getEmailAddress());
        $this->assertEquals(Customer::STATUS_ACTIVE, $customer->getStatus());
        $this->assertFalse($customer->getIsAnonymous());
    }
}
