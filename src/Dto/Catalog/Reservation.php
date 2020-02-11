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
 * @method string getProductCode()
 * @method string getLocationCode()
 * @method string getSku()
 * @method string getSalesOrderLineItemCode()
 * @method string getSalesOrderNumber()
 * @method string getFulfillmentOrderNumber()
 * @method string getBin()
 * @method string getBinLocation()
 * @method int getQuantity()
 * @method string getCode()
 *
 * @method $this setProductCode(string $productCode)
 * @method $this setLocationCode(string $locationCode)
 * @method $this setSku(string $sku)
 * @method $this setSalesOrderLineItemCode(string $salesOrderLineItemCode)
 * @method $this setSalesOrderNumber(string $salesOrderNumber)
 * @method $this setFulfillmentOrderNumber(string $fulfillmentOrderId)
 * @method $this setBin(string $bin)
 * @method $this setBinLocation(string $binLocation)
 * @method $this setQuantity(int $quantity)
 * @method $this setCode(string $code)
 *
 * @package Shopgate\ConnectSdk\Dto\Catalog
 *
 * @codeCoverageIgnore
 */
class Reservation extends Base
{
}
