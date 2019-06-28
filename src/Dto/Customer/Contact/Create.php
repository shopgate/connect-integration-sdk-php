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

namespace Shopgate\ConnectSdk\Dto\Customer\Contact;

use Shopgate\ConnectSdk\Dto\Customer\Contact;

/**
 * Default class that handles validation for contact Create payloads.
 *
 * @method Create setExternalContactCode(string $externalContactCode)
 * @method Create setStatus(string $status)
 * @method Create setFirstName(string $firstName)
 * @method Create setMiddleName(string $middleName)
 * @method Create setLastName(string $lastName)
 * @method Create setCompanyName(string $companyName)
 * @method Create setAddress1(string $address1)
 * @method Create setAddress2(string $address2)
 * @method Create setAddress3(string $address3)
 * @method Create setAddress4(string $address4)
 * @method Create setCity(string $city)
 * @method Create setPostalCode(string $postalCode)
 * @method Create setRegion(string $region)
 * @method Create setCountry(string $country)
 * @method Create setPhone(string $phone)
 * @method Create setFax(string $fax)
 * @method Create setMobile(string $mobile)
 * @method Create setEmailAddress(string $emailAddress)
 * @method Create setIsDefaultShipping(boolean $isDefaultShipping)
 * @method Create setIsDefaultBilling(boolean $isDefaultBilling)
 */
class Create extends Contact
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'externalContactCode' => ['type' => 'string'],
            'status'              => ['type' => 'string'],
            'firstName'           => ['type' => 'string'],
            'middleName'          => ['type' => 'string'],
            'lastName'            => ['type' => 'string'],
            'companyName'         => ['type' => 'string'],
            'address1'            => ['type' => 'string'],
            'address2'            => ['type' => 'string'],
            'address3'            => ['type' => 'string'],
            'address4'            => ['type' => 'string'],
            'city'                => ['type' => 'string'],
            'postalCode'          => ['type' => 'string'],
            'region'              => ['type' => 'string'],
            'country'             => ['type' => 'string'],
            'phone'               => ['type' => 'string'],
            'fax'                 => ['type' => 'string'],
            'mobile'              => ['type' => 'string'],
            'emailAddress'        => ['type' => 'string'],
            'isDefaultShipping'   => ['type' => 'boolean'],
            'isDefaultBilling'    => ['type' => 'boolean'],
        ],
        'additionalProperties' => true,
    ];
}
