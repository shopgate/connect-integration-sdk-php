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
 * @inheritDoc
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
            'status'                 => [
                'type' => 'string',
                'enum' => [
                    self::STATUS_ACTIVE,
                    self::STATUS_INACTIVE,
                    self::STATUS_DELETED,
                ],
            ],
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
