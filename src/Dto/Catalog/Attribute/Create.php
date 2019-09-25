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

namespace Shopgate\ConnectSdk\Dto\Catalog\Attribute;

use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;

/**
 * @method string getCode()
 * @method string getType()
 * @method string getUse()
 * @method Dto\Name getName()
 * @method string getExternalUpdateDate()
 * @method AttributeValue\Create[] getValues()
 *
 * @method Create setCode(string $code)
 * @method Create setType(string $type)
 * @method Create setUse(string $use)
 * @method Create setName(Attribute\Dto\Name $name)
 * @method Create setExternalUpdateDate(string $externalUpdateDate)
 * @method Create setValues(AttributeValue\Create[] $values)
 *
 * @codeCoverageIgnore
 */
class Create extends Attribute
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'               => ['type' => 'string'],
            'type'               => ['type' => 'string'],
            'use'                => ['type' => 'string'],
            'name'               => ['$ref' => Dto\Name::class],
            'externalUpdateDate' => ['type' => 'string'],
            'values'             => [
                'type'  => 'array',
                'items' => ['$ref' => AttributeValue\Create::class]
            ],
        ],
        'additionalProperties' => true,
    ];
}
