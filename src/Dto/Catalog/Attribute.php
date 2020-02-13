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
 * @method string getCode()
 * @method string getType()
 * @method string getUse()
 * @method string getName()
 * @method AttributeValue\Get[] getValues()
 *
 * @method $this setType(string $type)
 * @method $this setUse(string $use)
 * @method $this setName(Attribute\Dto\Name $name)
 * @method $this setValues(AttributeValue\Get[] $values)
 */
class Attribute extends Base
{
    const USE_OPTION = 'option';
    const USE_EXTRA = 'extra';
    const USE_PROPERTY = 'property';
    const TYPE_TEXT = 'text';
    const TYPE_PRODUCT_LIST = 'productList';
    const TYPE_INPUT = 'input';
    const TYPE_NUMBER = 'number';
}
