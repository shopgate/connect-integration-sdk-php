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

namespace Shopgate\ConnectSdk\Dto\Customer\Customer;

use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Customer\Customer\Dto\Attribute;
use Shopgate\ConnectSdk\Dto\Customer\Customer\Dto\Settings;
use Shopgate\ConnectSdk\Dto\Customer\Contact;

/**
 * @method Create setExternalCustomerNumber(string $externalCustomerNumber)
 * @method Create setFirstName(string $firstName)
 * @method Create setMiddleName(string $middleName)
 * @method Create setLastName(string $lastName)
 * @method Create setEmailAddress(string $emailAddress)
 * @method Create setStatus(string $status)
 * @method Create setIsAnonymous(boolean $isAnonymous)
 * @method Create setContacts(Contact[] $contacts)
 * @method Create setAttributes(Attribute[] $attributes)
 * @method Create setSettings(Settings $settings)
 */
class Create extends Customer
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'externalCustomerNumber' => ['type' => 'string'],
            'firstName'              => ['type' => 'string'],
            'middleName'             => ['type' => 'string'],
            'lastName'               => ['type' => 'string'],
            'emailAddress'           => ['type' => 'string'],
            'status'                 => [
                'type' => 'string',
                'enum' => [
                    self::STATUS_ACTIVE,
                    self::STATUS_INACTIVE,
                    self::STATUS_DELETED,
                ],
            ],
            'isAnonymous'            => ['type' => 'boolean'],
            'contacts'               => ['type' => 'array'],
            'attributes'             => ['type' => 'array'],
            'settings'               => ['type' => 'object'],
        ],
        'additionalProperties' => true,
    ];
}
