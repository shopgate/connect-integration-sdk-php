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
use Shopgate\ConnectSdk\Dto\Location\Location\Dto\Type;
use Shopgate\ConnectSdk\Dto\Location\Location\Dto\OperationHours;
use Shopgate\ConnectSdk\Dto\Location\Location\Dto\Details;
use Shopgate\ConnectSdk\Dto\Location\Location\Dto\Address;
use Shopgate\ConnectSdk\Dto\Location\Location\Dto\Inventory;
use Shopgate\ConnectSdk\Dto\Location\Location\Dto\Settings;

/**
 * @method Update setCode(string $code)
 * @method Update setName(string $name)
 * @method Update setType(Type $type)
 * @method Update setStatus(string $status)
 * @method Update setLatitude(string $latitude)
 * @method Update setLongitude(string $longitude)
 * @method Update setOperationHours(OperationHours $operationHours)
 * @method Update setLocaleCode(string $localeCode)
 * @method Update setTimeZone(string $timeZone)
 * @method Update setDetails(Details $details)
 * @method Update setAddresses(Address[] $addresses)
 * @method Update setInventory(Inventory $inventory)
 * @method Update setSupportedFulfillmentMethods(string[] $supportedFulfillmentMethods)
 * @method Update setSettings(Settings $settings)
 * @method Update setIsDefault(bool $isDefault)
 *
 * @codeCoverageIgnore
 */

class Update extends Location
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'                        => ['type' => 'string'],
            'name'                        => ['type' => 'string'],
            'type'                        => ['type' => 'object'],
            'status'                      => ['type' => 'string'],
            'latitude'                    => ['type' => 'string'],
            'longitude'                   => ['type' => 'string'],
            'operationHours'              => ['type' => 'object'],
            'localeCode'                  => ['type' => 'string'],
            'timeZone'                    => ['type' => 'string'],
            'details'                     => ['type' => 'object'],
            'addresses'                   => ['type' => 'array'],
            'inventory'                   => ['type' => 'object'],
            'supportedFulfillmentMethods' => ['type' => 'array'],
            'settings'                    => ['type' => 'object'],
            'isDefault'                   => ['type' => 'boolean']
        ],
        'additionalProperties' => true
    ];
}
