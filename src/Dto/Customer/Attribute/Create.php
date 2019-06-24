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

namespace Shopgate\ConnectSdk\Dto\Customer\Attribute;

use Shopgate\ConnectSdk\Dto\Customer\Attribute;

/**
 * Default class that handles validation for attribute Create payloads.
 *
 * @method Create setType(string $type)
 * @method Create setIsRequired(boolean $isRequired)
 * @method Create setName(string $name)
 * @method Create setCode(string $code)
 * @method Create setValues(AttributeValues[] $values)
 */
class Create extends Attribute
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'type'       => ['type' => 'string'],
            'isRequired' => ['type' => 'boolean'],
            'name'       => ['type' => 'string'],
            'code'       => ['type' => 'string'],
            'values'     => ['type' => 'array'],
        ],
        'additionalProperties' => true,
    ];
}
