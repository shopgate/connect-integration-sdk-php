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

namespace Shopgate\ConnectSdk\DTO\Catalog\Product;

use Shopgate\ConnectSdk\DTO\Base as DTOBase;

class AbstractProduct extends DTOBase
{
    const MODEL_TYPE_STANDARD                       = 'standard';
    const MODEL_TYPE_CONFIGURABLE                   = 'configurable';
    const MODEL_TYPE_BUNDLE                         = 'bundle';
    const MODEL_TYPE_BUNDLE_ITEM                    = 'bundleItem';
    const MODEL_TYPE_VARIANT                        = 'variant';
    const STATUS_ACTIVE                             = 'active';
    const STATUS_INACTIVE                           = 'inactive';
    const STATUS_DELETED                            = 'deleted';
    const STATUS_SCHEDULED                          = 'scheduled';
    const INVENTORY_TREATMENT_SHOW_OUT_OF_STOCK     = 'showOutOfStock';
    const INVENTORY_TREATMENT_ALLOW_BACK_ORDERS     = 'allowBackOrders';
    const INVENTORY_TREATMENT_PRE_ORDER             = 'preOrder';
    const FULFILLMENT_METHOD_SIMPLE_PICKUP_IN_STORE = 'simplePickUpInStore';
    const FULFILLMENT_METHOD_DIRECT_SHIP            = 'directShip';
}
