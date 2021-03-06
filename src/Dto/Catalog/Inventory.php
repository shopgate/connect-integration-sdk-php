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

namespace Shopgate\ConnectSdk\Dto\Catalog;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method $this setProductCode(string $productCode)
 * @method $this setLocationCode(string $locationCode)
 * @method $this setSku(string $sku)
 * @method $this setOnHand(integer $onHand)
 * @method $this setBin(string $bin)
 * @method $this setBinLocation(string $binLocation)
 *
 * @package Shopgate\ConnectSdk\Dto\Catalog
 */
class Inventory extends Base
{
    const OPERATION_TYPE_INCREMENT = 'increment';
    const OPERATION_TYPE_DECREMENT = 'decrement';
}
