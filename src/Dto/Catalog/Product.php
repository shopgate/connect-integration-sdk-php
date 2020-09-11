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

use Shopgate\ConnectSdk\Dto\Base as DtoBase;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;

/**
 * @method Dto\Name getName()
 * @method Dto\LongName getLongName()
 * @method Dto\LongDescription getLongDescription()
 * @method Dto\ShortDescription getShortDescription()
 * @method Dto\Categories[] getCategories()
 * @method Dto\Properties[] getProperties()
 * @method Dto\MediaList getMedia()
 * @method Dto\Options[] getOptions()
 * @method Dto\Extras[] getExtras()
 * @method string getCode()
 * @method string getParentProductCode()
 * @method string getCatalogCode()
 * @method string getModelType()
 * @method Dto\Identifiers getIdentifiers()
 * @method Dto\Price getPrice()
 * @method string[] getFulfillmentMethods()
 * @method string getUnit()
 * @method float getUnitValue()
 * @method string getUnitPriceRefUom()
 * @method float getUnitPriceRefValue()  
 * @method boolean getHasCatchWeight()  
 * @method boolean getIsSerialized()
 * @method string getStatus()
 * @method string getStartDate()
 * @method string getEndDate()
 * @method string getFirstAvailableDate()
 * @method string getEolDate()
 * @method boolean getIsInventoryManaged()
 * @method string getInventoryTreatment()
 * @method Dto\ShippingInformation getShippingInformation()
 * @method float getRating()
 * @method string getUrl()
 * @method boolean getIsTaxed()
 * @method string getTaxClass()
 * @method float getMinQty()
 * @method float getMaxQty()
 * @method string getExternalUpdateDate()
 * @method int getSequenceId()
 *
 * @method $this setName(Dto\Name $name)
 * @method $this setLongName(Dto\LongName $longName)
 * @method $this setShortDescription(Dto\ShortDescription $name)
 * @method $this setLongDescription(Dto\LongDescription $longName)
 * @method $this setCategories(Dto\Categories[] $categories)
 * @method $this setProperties(Dto\Properties[] $properties)
 * @method $this setMedia(Dto\MediaList $media)
 * @method $this setOptions(Dto\Options[] $options)
 * @method $this setExtras(Dto\Extras[] $extras)
 * @method $this setCode(string $code)
 * @method $this setParentProductCode(string $parentProductCode)
 * @method $this setCatalogCode(string $catalogCode)
 * @method $this setModelType(string $modelType)
 * @method $this setIdentifiers(Dto\Identifiers $identifiers)
 * @method $this setPrice(Dto\Price $price)
 * @method $this setFulfillmentMethods(string[] $fulfillmentMethods)
 * @method $this setUnit(string $unit)
 * @method $this setUnitValue(float $unitValue)
 * @method $this setUnitPriceRefUom(string $unitPriceRefUom)
 * @method $this setUnitPriceRefValue(float $unitPriceRefValue)
 * @method $this setHasCatchWeight(boolean $hasCatchWeight)
 * @method $this setIsSerialized(boolean $isSerialized)
 * @method $this setStatus(string $status)
 * @method $this setStartDate(string $startDate)
 * @method $this setEndDate(string $endDate)
 * @method $this setFirstAvailableDate(string $firstAvailableDate)
 * @method $this setEolDate(string $eolDate)
 * @method $this setIsInventoryManaged(boolean $isInventoryManaged)
 * @method $this setInventoryTreatment(string $inventoryTreatment)
 * @method $this setShippingInformation(Dto\ShippingInformation $shippingInformation)
 * @method $this setRating(float $rating)
 * @method $this setUrl(string $url)
 * @method $this setIsTaxed(boolean $isTaxed)
 * @method $this setTaxClass(string $taxClass)
 * @method $this setMinQty(float $minQty)
 * @method $this setMaxQty(float $maxQty)
 * @method $this setExternalUpdateDate(string $externalUpdateDate)
 *
 * @method Dto\Inventory[] getInventories() - for getter only
 *
 * @codeCoverageIgnore
 */
class Product extends DtoBase
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
