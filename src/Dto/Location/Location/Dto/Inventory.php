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
 * @method Inventory setIsManaged(bool $isManaged)
 * @method Inventory setMode(string $mode)
 * @method Inventory setSafetyStockMode(string $safetyStockMode)
 * @method Inventory setSafetyStock(int $safetyStock)
 * @method Inventory setSafetyStockType(string $safetyStockType)
 * @method bool getIsManaged()
 * @method string getMode()
 * @method string getSafetyStockMode()
 * @method int getSafetyStock()
 * @method string getSafetyStockType()
 *
 * @codeCoverageIgnore
 */
class Inventory extends Base
{
    const MODE_BLIND                 = 'blind';
    const MODE_INTEGRATED            = 'integrated';
    const SAFETY_STOCK_MODE_ENABLED  = 'enabled';
    const SAFETY_STOCK_MODE_DISABLED = 'disabled';
    const SAFETY_STOCK_TYPE_PERCENT  = 'percentage';
    const SAFETY_STOCK_TYPE_COUNT    = 'count';

    /**
     * @var array
     */
    protected $schema = [
            'type'                 => 'object',
            'properties'           => [
                'isManaged'       => ['type' => 'bool'],
                'mode'            => ['type' => 'string'],
                'safetyStockMode' => ['type' => 'string'],
                'safetyStock'     => ['type' => 'int'],
                'safetyStockType' => ['type' => 'string']
            ],
            'additionalProperties' => true,
        ];

}
