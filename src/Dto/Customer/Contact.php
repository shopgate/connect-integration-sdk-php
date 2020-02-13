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

namespace Shopgate\ConnectSdk\Dto\Customer;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method $this setExternalContactCode(string $externalContactCode)
 * @method $this setStatus(string $status)
 * @method $this setFirstName(string $firstName)
 * @method $this setMiddleName(string $middleName)
 * @method $this setLastName(string $lastName)
 * @method $this setCompanyName(string $companyName)
 * @method $this setAddress1(string $address1)
 * @method $this setAddress2(string $address2)
 * @method $this setAddress3(string $address3)
 * @method $this setAddress4(string $address4)
 * @method $this setCity(string $city)
 * @method $this setPostalCode(string $postalCode)
 * @method $this setRegion(string $region)
 * @method $this setCountry(string $country)
 * @method $this setPhone(string $phone)
 * @method $this setFax(string $fax)
 * @method $this setMobile(string $mobile)
 * @method $this setEmailAddress(string $emailAddress)
 * @method $this setIsDefaultShipping(boolean $isDefaultShipping)
 * @method $this setIsDefaultBilling(boolean $isDefaultBilling)
 *
 * @package Shopgate\ConnectSdk\Dto\Customer
 */
class Contact extends Base
{
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED  = 'deleted';
}
