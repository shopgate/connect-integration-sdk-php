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

namespace Shopgate\ConnectSdk\DTO\Catalog\Product\Property;

use Dto\RegulatorInterface;
use Shopgate\ConnectSdk\DTO\Base as DTOBase;

/**
 * @method Base setCode(string $code)
 * @method Base setName(Name $name)
 * @method Base setValue(LocalizationValue $value)
 * @method Base setDisplayGroup(string $displayGroup)
 * @method Base setSubDisplayGroup(LocalizationSubDisplayGroup $subDisplayGroup)
 * @method Base setIsPriced(boolean $isPriced)
 * @method Base setAttributePrice(float $attributePrice)
 * @method Base setUnit(string $unit)
 */
class Base extends DTOBase
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'            => ['type' => 'string'],
            'name'            => ['type' => 'object'],
            'value'           => ['type' => 'object'],
            'type'            => ['type' => 'string'],
            'displayGroup'    => ['type' => 'string'],
            'subDisplayGroup' => ['type' => 'object'],
            'isPriced'        => ['type' => 'boolean'],
            'attributePrice'  => ['type' => 'number'],
            'unit'            => ['type' => 'string'],
        ],
        'default'              => [
            'type' => 'base',
        ],
        'additionalProperties' => true,
    ];
}
