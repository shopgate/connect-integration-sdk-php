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

namespace Shopgate\ConnectSdk\DTO\Catalog\Attribute;

use Shopgate\ConnectSdk\DTO\Base as DTOBase;

/**
 * Default class that handles validation for attribute Create payloads.
 *
 * @method string setCode(string $code)
 * @method string setType(string $type)
 * @method string setUse(string $use)
 * @method string setName(Name $name)
 * @method string setExternalUpdateDate(string $externalUpdateDate)
 * @method string setValues(string $values)
 */
class Create extends DTOBase
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'               => ['type' => 'string'],
            'type'               => ['type' => 'string'],
            'use'                => ['type' => 'string'],
            'name'               => ['type' => 'object'],
            'externalUpdateDate' => ['type' => 'string'],
            'values'             => ['type' => 'string'],
        ],
        'additionalProperties' => true,
    ];
}