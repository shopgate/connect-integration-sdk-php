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
 * @method string getId()
 * @method string getExternalCustomerNumber()
 * @method string getFirstName()
 * @method string getMiddleName()
 * @method string getLastName()
 * @method string getEmailAddress()
 * @method string getStatus()
 * @method boolean getIsAnonymous()
 * @method string getExternalUpdateDate()
 * @method Contact[] getContacts()
 * @method Customer\Dto\Attribute[] getAttributes()
 * @method Customer\Dto\Settings getSettings()
 *
 * @method $this setExternalCustomerNumber(string $externalCustomerNumber)
 * @method $this setFirstName(string $firstName)
 * @method $this setMiddleName(string $middleName)
 * @method $this setLastName(string $lastName)
 * @method $this setEmailAddress(string $emailAddress)
 * @method $this setStatus(string $status)
 * @method $this setIsAnonymous(boolean $isAnonymous)
 * @method $this setContacts(Contact[] $contacts)
 * @method $this setAttributes(Customer\Dto\Attribute[] $attributes)
 * @method $this setSettings(Customer\Dto\Settings $settings)
 *
 * @package Shopgate\ConnectSdk\Dto\Customer
 */
class Customer extends Base
{
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED  = 'deleted';
}
