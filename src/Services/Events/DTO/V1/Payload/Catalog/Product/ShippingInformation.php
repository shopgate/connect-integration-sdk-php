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

namespace Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Product;

use Shopgate\ConnectSdk\Services\Events\DTO\Base as DTOBase;

/**
 * @method ShippingInformation setIsShippedAlone(boolean $isShippedAlone)
 * @method ShippingInformation setHeight(number $height)
 * @method ShippingInformation setHeightUnit(string $heightUnit)
 * @method ShippingInformation setWidth(number $width)
 * @method ShippingInformation setWidthUnit(string $widthUnit)
 * @method ShippingInformation setLength(number $length)
 * @method ShippingInformation setLengthUnit(string $lengthUnit)
 * @method ShippingInformation setWeight(number $weight)
 * @method ShippingInformation setWeightUnit(string $weightUnit)
 */
class ShippingInformation extends DTOBase
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'isShippedAlone' => ['type' => 'boolean'],
            'height'         => ['type' => 'number'],
            'heightUnit'     => ['type' => 'string'],
            'width'          => ['type' => 'number'],
            'widthUnit'      => ['type' => 'string'],
            'length'         => ['type' => 'number'],
            'lengthUnit'     => ['type' => 'string'],
            'weight'         => ['type' => 'number'],
            'weightUnit'     => ['type' => 'string']
        ],
        'additionalProperties' => true
    ];
}
