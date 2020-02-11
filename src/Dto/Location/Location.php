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

namespace Shopgate\ConnectSdk\Dto\Location;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Location\Dto\Type getType()
 * @method string getCode()
 * @method string getName()
 * @method string getStatus()
 * @method string getLatitude()
 * @method string getLongitude()
 * @method Location\Dto\OperationHours getOperationHours()
 * @method string getLocaleCode()
 * @method string getTimeZone()
 * @method Location\Dto\Details getDetails()
 * @method Location\Dto\Address[] getAddresses()
 * @method Location\Dto\Inventory getInventory()
 * @method string[] getSupportedFulfillmentMethods()
 * @method Location\Dto\Settings getSettings()
 * @method bool getIsComingSoon()
 * @method bool getIsDefault()
 *
 * @method $this setType(Location\Dto\Type $type)
 * @method $this setCode(string $code)
 * @method $this setName(string $name)
 * @method $this setStatus(string $status)
 * @method $this setLatitude(string $latitude)
 * @method $this setLongitude(string $longitude)
 * @method $this setOperationHours(Location\Dto\OperationHours $operationHours)
 * @method $this setLocaleCode(string $localeCode)
 * @method $this setTimeZone(string $timeZone)
 * @method $this setDetails(Location\Dto\Details $details)
 * @method $this setAddresses(Location\Dto\Address[] $addresses)
 * @method $this setInventory(Location\Dto\Inventory $inventory)
 * @method $this setSupportedFulfillmentMethods(string[] $supportedFulfillmentMethods)
 * @method $this setSettings(Location\Dto\Settings $settings)
 * @method $this setIsComingSoon(bool $isComingSoon)
 * @method $this setIsDefault(bool $isDefault)
 *
 * @package Shopgate\ConnectSdk\Dto\Location
 */
class Location extends Base
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ONHOLD = 'onhold';
    const STATUS_DELETED = 'deleted';
    const TYPE_STORE = 'store';
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_DROP_SHIPPING = 'dropShipping';
    const TYPE_3RD_PARTY_FULFILLMENT = '3rdPartyFulfillment';
}
