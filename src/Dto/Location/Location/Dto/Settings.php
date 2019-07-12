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

    /**
     * @param bool $enableInStorePickup
     * @param bool $enableShipFromStore
     * @param bool $enableInLocationFinder
     * @param bool $enableInventoryBrowse
     * @param bool $enableForRelate
     * @param bool $showStoreHours
     *
     * @return $this
     */
    public function add($enableInStorePickup, $enableShipFromStore, $enableInLocationFinder, $enableInventoryBrowse, $enableForRelate, $showStoreHours)
    {
        $this->set('enableInStorePickup', (bool)$enableInStorePickup);
        $this->set('enableShipFromStore', (bool)$enableShipFromStore);
        $this->set('enableInLocationFinder', (bool)$enableInLocationFinder);
        $this->set('enableInventoryBrowse', (bool)$enableInventoryBrowse);
        $this->set('enableForRelate', (bool)$enableForRelate);
        $this->set('showStoreHours', (bool)$showStoreHours);

        return $this;
    }
}
