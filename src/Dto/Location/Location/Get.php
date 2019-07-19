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

namespace Shopgate\ConnectSdk\Dto\Location\Location;

use Shopgate\ConnectSdk\Dto\Location\Location;

/**
 * @method string getCode()
 * @method string getName()
 * @method Dto\Type getType()
 * @method string getStatus()
 * @method string getLatitude()
 * @method string getLongitude()
 * @method Dto\OperationHours getOperationHours()
 * @method string getLocaleCode()
 * @method string getTimeZone()
 * @method Dto\Details getDetails()
 * @method Dto\Address[] getAddresses()
 * @method object getInventory()
 * @method string[] getSupportedFulfillmentMethods()
 * @method Dto\Settings getSettings()
 * @method bool getIsDefault()
 *
 * @codeCoverageIgnore
 */
class Get extends Location
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'                        => ['type' => 'string'],
            'name'                        => ['type' => 'string'],
            'type'                        => ['$ref' => Dto\Type::class],
            'status'                      => ['type' => 'string'],
            'latitude'                    => ['type' => 'string'],
            'longitude'                   => ['type' => 'string'],
            'operationHours'              => ['$ref' => Dto\OperationHours::class],
            'localeCode'                  => ['type' => 'string'],
            'timeZone'                    => ['type' => 'string'],
            'details'                     => ['$ref' => Dto\Details::class],
            'addresses'                   => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Address::class]
            ],
            'inventory'                   => ['$ref' => Dto\Inventory::class],
            'supportedFulfillmentMethods' => [
                'type' => 'array',
                'items' => ['type' => 'string']
            ],
            'settings'                    => ['$ref' => Dto\Settings::class],
            'isDefault'                   => ['type' => 'boolean']
        ],
        'additionalProperties' => true
    ];
}
