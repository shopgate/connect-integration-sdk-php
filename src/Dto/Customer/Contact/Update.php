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
 * Default class that handles validation for contact Update payloads.
 *
 * @method Update setExternalContactCode(string $externalContactCode)
 * @method Update setStatus(string $status)
 * @method Update setFirstName(string $firstName)
 * @method Update setMiddleName(string $middleName)
 * @method Update setLastName(string $lastName)
 * @method Update setCompanyName(string $companyName)
 * @method Update setAddress1(string $address1)
 * @method Update setAddress2(string $address2)
 * @method Update setAddress3(string $address3)
 * @method Update setAddress4(string $address4)
 * @method Update setCity(string $city)
 * @method Update setPostalCode(string $postalCode)
 * @method Update setRegion(string $region)
 * @method Update setCountry(string $country)
 * @method Update setPhone(string $phone)
 * @method Update setFax(string $fax)
 * @method Update setMobile(string $mobile)
 * @method Update setEmailAddress(string $emailAddress)
 * @method Update setIsDefaultShipping(string $isDefaultShipping)
 * @method Update setIsDefaultBilling(string $isDefaultBilling)
 */
class Update extends Contact
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
