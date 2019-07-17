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

namespace Shopgate\ConnectSdk\Dto\Location\Location\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Settings setEnableInStorePickup(string $enableInStorePickup)
 * @method Settings setEnableShipFromStore(string $enableShipFromStore)
 * @method Settings setEnableInLocationFinder(string $enableInLocationFinder)
 * @method Settings setEnableInventoryBrowse(string $enableInventoryBrowse)
 * @method Settings setEnableForRelate(string $enableForRelate)
 * @method Settings setShowStoreHours(string $showStoreHours)
 * @method bool getEnableInStorePickup()
 * @method bool getEnableShipFromStore()
 * @method bool getEnableInLocationFinder()
 * @method bool getEnableInventoryBrowse()
 * @method bool getEnableForRelate()
 * @method bool getShowStoreHours()
 *
 * @codeCoverageIgnore
 */
class Settings extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties'           => [
            'enableInStorePickup'    => ['type' => 'bool'],
            'enableShipFromStore'    => ['type' => 'bool'],
            'enableInLocationFinder' => ['type' => 'bool'],
            'enableInventoryBrowse'  => ['type' => 'bool'],
            'enableForRelate'        => ['type' => 'bool'],
            'showStoreHours'         => ['type' => 'bool'],
        ],
        'additionalProperties' => true,
    ];
}
