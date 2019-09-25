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

namespace Shopgate\ConnectSdk\Dto\Catalog\ParentCatalog;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Create setCode(string $code)
 * @method Create setName(string $name)
 * @method Create setIsDefault(boolean $isDefault)
 * @method Create setDefaultLocaleCode(string $defaultLocaleCode)
 * @method Create setDefaultCurrencyCode(string $defaultCurrencyCode)
 *
 * @codeCoverageIgnore
 */
class Create extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'code' => ['type' => 'string'],
            'name' => ['type' => 'string'],
            'isDefault' => ['type' => 'boolean'],
            'defaultLocaleCode' => ['type' => 'string'],
            'defaultCurrencyCode' => ['type' => 'string'],
        ],
        'additionalProperties' => true
    ];
}
