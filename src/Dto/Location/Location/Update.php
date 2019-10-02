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
 * @method Update setCode(string $code)
 * @method Update setName(string $name)
 * @method Update setType(Dto\Type $type)
 * @method Update setStatus(string $status)
 * @method Update setLatitude(float $latitude)
 * @method Update setLongitude(float $longitude)
 * @method Update setOperationHours(Dto\OperationHours $operationHours)
 * @method Update setLocaleCode(string $localeCode)
 * @method Update setTimeZone(string $timeZone)
 * @method Update setDetails(Dto\Details $details)
 * @method Update setAddresses(Dto\Address[] $addresses)
 * @method Update setInventory(Dto\Inventory $inventory)
 * @method Update setSupportedFulfillmentMethods(string[] $supportedFulfillmentMethods)
 * @method Update setSettings(Dto\Settings $settings)
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
        'type' => 'object',
        'properties' => [
            'code' => ['type' => 'string'],
            'name' => ['type' => 'string'],
            'type' => ['$ref' => Dto\Type::class],
            'status' => ['type' => 'string'],
            'latitude' => ['type' => 'number'],
            'longitude' => ['type' => 'number'],
            'operationHours' => ['$ref' => Dto\OperationHours::class],
            'localeCode' => ['type' => 'string'],
            'timeZone' => ['type' => 'string'],
            'details' => ['$ref' => Dto\Details::class],
            'addresses' => [
                'type' => 'array',
                'items' => ['$ref' => Dto\Address::class]
            ],
            'inventory' => ['$ref' => Dto\Inventory::class],
            'supportedFulfillmentMethods' => [
                'type' => 'array',
                'items' => ['type' => 'string']
            ],
            'settings' => ['$ref' => Dto\Settings::class],
            'isDefault' => ['type' => 'boolean']
        ],
        'additionalProperties' => true
    ];
}
