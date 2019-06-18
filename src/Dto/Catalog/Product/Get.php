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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product;

use Shopgate\ConnectSdk\Dto\Catalog\Product;

/**
 * Dto for product response.
 *
 * @method string getCode()
 * @method string getParentProductCode()
 * @method string getCatalogCode()
 * @method string getModelType()
 * @method Dto\Identifiers getIdentifiers()
 * @method Dto\Price getPrice()
 * @method string[] getFulfillmentMethods()
 * @method string getUnit()
 * @method boolean getIsSerialized()
 * @method string getStatus()
 * @method string getStartDate()
 * @method string getEndDate()
 * @method string getFirstAvailableDate()
 * @method string getEolDate()
 * @method boolean getIsInventoryManaged()
 * @method string getInventoryTreatment()
 * @method Dto\ShippingInformation getShippingInformation()
 * @method number getRating()
 * @method string getUrl()
 * @method boolean getTaxClass()
 * @method number getMinQty()
 * @method number getMaxQty()
 * @method string getName()
 * @method string getLongName()
 * @method Dto\Categories getCategories()
 * @method Dto\Properties[] getProperties()
 * @method Dto\Media getMedia()
 * @method Dto\Inventory[] getInventories()
 * @method Dto\Options[] getOptions()
 * @method Dto\Extras[] getExtras()
 */
class Get extends Product
{
}
