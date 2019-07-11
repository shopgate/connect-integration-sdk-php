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

namespace Shopgate\ConnectSdk\Dto\Customer\Customer\Dto;

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Customer\Customer\Dto\Attribute\Value;

/**
 * Dto for customer attribute.
 *
 * @method Attribute setCode(string $code)
 * @method Attribute setName(string $name)
 * @method string getCode()
 * @method string getName()
 *
 * @codeCoverageIgnore
 */
class Attribute extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'  => ['type' => 'string'],
            'value' => ['type' => ['string', 'object']],
            'name'  => ['type' => 'string'],
        ],
        'additionalProperties' => true,
    ];

    /**
     * @param Value|string $value
     *
     * @return Attribute
     */
    public function setValue($value)
    {
        if (is_object($value)) {
            return $this::set('value', new Value($value));
        }

        return $this::set('value', $value);
    }

    /**
     * @return Value|string|null
     */
    public function getValue()
    {
        $value = $this::get('value');
        if (is_object($value)) {
            return new Value($value);
        }

        return $value;
    }
}
