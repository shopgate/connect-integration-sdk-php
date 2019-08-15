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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto\FulfillmentGroup\Fulfillment;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method FulfillmentPackage setId(string $id)
 * @method FulfillmentPackage setStatus(string $status)
 * @method FulfillmentPackage setServiceLevel(string $serviceLevel)
 * @method FulfillmentPackage setFulfilledFromLocationCode(string $fulfilledFromLocationCode)
 * @method FulfillmentPackage setWeight(float $weight)
 * @method FulfillmentPackage setDimensions(string $dimensions)
 * @method FulfillmentPackage setTracking(string $tracking)
 * @method FulfillmentPackage setPickUpBy(string $pickUpBy)
 * @method FulfillmentPackage setLabelUrl(string $labelUrl)
 * @method FulfillmentPackage setFulfilledDate(string $fulfilledDate)
 * @method FulfillmentPackage setPackageItems(FulfillmentPackage\PackageItem[] $packageItems)
 * @method string getId()
 * @method string getStatus()
 * @method string getServiceLevel()
 * @method string getFulfilledFromLocationCode()
 * @method float getWeight()
 * @method string getDimensions()
 * @method string getTracking()
 * @method string getPickUpBy()
 * @method string getLabelUrl()
 * @method string getFulfilledDate()
 * @method FulfillmentPackage\PackageItem[] getPackageItems()
 *
 * @codeCoverageIgnore
 */
class FulfillmentPackage extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'string'],
            'status' => ['type' => 'string'],
            'serviceLevel' => ['type' => 'string'],
            'fulfilledFromLocationCode' => ['type' => 'string'],
            'weight' => ['type' => 'number'],
            'dimensions' => ['type' => 'string'],
            'tracking' => ['type' => 'string'],
            'pickUpBy' => ['type' => 'string'],
            'labelUrl' => ['type' => 'string'],
            'fulfilledDate' => ['type' => 'string'],
            'packageItems' => [
                'type' => 'array',
                'items' => ['$ref' => FulfillmentPackage\PackageItem::class]
            ]
        ],
        'additionalProperties' => true,
    ];
}
