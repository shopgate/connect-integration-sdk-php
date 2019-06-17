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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\SubDisplayGroup;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Name;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Value;

/**
 * @method Properties setCode(string $code)
 * @method Properties setName(Name $name)
 * @method Properties setValue(Value $value)
 * @method Properties setDisplayGroup(string $displayGroup)
 * @method Properties setSubDisplayGroup(SubDisplayGroup $subDisplayGroup)
 * @method Properties setIsPriced(boolean $isPriced)
 * @method Properties setAttributePrice(float $attributePrice)
 * @method Properties setUnit(string $unit)
 */
class Properties extends Base
{
    const DISPLAY_GROUP_PROPERTIES = 'properties';
    const DISPLAY_GROUP_FEATURES = 'features';
    const DISPLAY_GROUP_GENERAL = 'general';
    const DISPLAY_GROUP_PRICING = 'pricing';

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
