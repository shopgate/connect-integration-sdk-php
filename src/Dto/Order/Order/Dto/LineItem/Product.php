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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto\LineItem;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Product setCode(string $code)
 * @method Product setName(string $name)
 * @method Product setImage(string $image)
 * @method Product setPrice(float $price)
 * @method Product setCurrencyCode(string $currencyCode)
 * @method string getCode()
 * @method string getName()
 * @method string getImage()
 * @method float getPrice()
 * @method string getCurrencyCode()
 *
 * @codeCoverageIgnore
 */
class Product extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'code' => ['type' => 'string'],
            'name' => ['type' => 'string'],
            'image' => ['type' => 'string'],
            'price' => ['type' => 'number'],
            'currencyCode' => ['type' => 'string']
        ],
        'additionalProperties' => true,
    ];
}
