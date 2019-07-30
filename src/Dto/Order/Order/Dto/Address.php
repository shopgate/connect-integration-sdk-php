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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Address setType(string $type)
 * @method Address setFirstName(string $firstName)
 * @method Address setMiddleName(string $middleName)
 * @method Address setLastName(string $lastName)
 * @method Address setCompany(string $company)
 * @method Address setAddress1(string $address1)
 * @method Address setAddress2(string $address2)
 * @method Address setAddress3(string $address3)
 * @method Address setAddress4(string $address4)
 * @method Address setCity(string $city)
 * @method Address setRegion(string $region)
 * @method Address setPostalCode(string $postalCode)
 * @method Address setCountry(string $country)
 * @method Address setPhone(string $phone)
 * @method Address setFax(string $fax)
 * @method Address setMobile(string $mobile)
 * @method Address setEmailAddress(string $emailAddress)
 * @method string getType()
 * @method string getFirstName()
 * @method string getMiddleName()
 * @method string getLastName()
 * @method string getAddress1()
 * @method string getAddress2()
 * @method string getAddress3()
 * @method string getAddress4()
 * @method string getCity()
 * @method string getRegion()
 * @method string getPostalCode()
 * @method string getCountry()
 * @method string getPhone()
 * @method string getFax()
 * @method string getMobile()
 * @method string getEmailAddress()
 *
 * @codeCoverageIgnore
 */
class Address extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'type' => ['type' => 'string'],
            'firstName' => ['type' => 'string'],
            'middleName' => ['type' => 'string'],
            'lastName' => ['type' => 'string'],
            'company' => ['type' => 'string'],
            'address1' => ['type' => 'string'],
            'address2' => ['type' => 'string'],
            'address3' => ['type' => 'string'],
            'address4' => ['type' => 'string'],
            'city' => ['type' => 'string'],
            'region' => ['type' => 'string'],
            'postalCode' => ['type' => 'string'],
            'country' => ['type' => 'string'],
            'phone' => ['type' => 'string'],
            'fax' => ['type' => 'string'],
            'mobile' => ['type' => 'string'],
            'emailAddress' => ['type' => 'string']
        ],
        'additionalProperties' => true,
    ];
}
