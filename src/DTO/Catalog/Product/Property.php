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

/**
 * @method Property setCode(string $code)
 * @method Property setName(Name $name)
 * @method Property setValue(LocalizationValue|string $value)
 * @method Property setType(string $type)
 * @method Property setDisplayGroup(string $displayGroup)
 * @method Property setSubDisplayGroup(LocalizationSubDisplayGroup $subDisplayGroup)
 */
class Property extends DTOBase
{
    const TYPE_SIMPLE       = 'simple';
    const TYPE_OPTION       = 'option';
    const TYPE_INPUT        = 'input';
    const TYPE_PRODUCT      = 'product';
    const TYPE_PRODUCT_LIST = 'productList';

    const DISPLAY_GROUP_PROPERTIES = 'properties';
    const DISPLAY_GROUP_FEATURES   = 'features';
    const DISPLAY_GROUP_GENERAL    = 'general';
    const DISPLAY_GROUP_PRICING    = 'pricing';

    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'            => ['type' => 'string'],
            'name'            => ['type' => 'object'],
            'value'           => ['type' => ['object', 'string']],
            'type'            => ['type' => 'string'],
            'displayGroup'    => ['type' => 'string'],
            'subDisplayGroup' => ['type' => 'object']
        ],
        'additionalProperties' => true
    ];
}
