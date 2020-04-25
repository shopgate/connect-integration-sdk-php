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

namespace Shopgate\ConnectSdk\Dto\Order;

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\DtoObject;

/**
 * @method FulfillmentOrderHistory setId(integer $id)
 * @method FulfillmentOrderHistory setType(string $eventName)
 * @method FulfillmentOrderHistory setDetails(string $details)
 * @method FulfillmentOrderHistory setNewValue(string $newValue)
 * @method FulfillmentOrderHistory setOldValue(string $oldValue)
 * @method FulfillmentOrderHistory setCreateDate(string $dateTime)
 * @method FulfillmentOrderHistory setEventUser(string $user)
 * @method integer getId()
 * @method string getType()
 * @method string getDetails()
 * @method DtoObject getNewValue()
 * @method DtoObject getOldValue()
 * @method string getCreateDate()
 * @method DtoObject getUser()
 *
 * @codeCoverageIgnore
 */
class FulfillmentOrderHistory extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'id'            => ['type' => 'number'],
            'type'          => ['type' => 'string'],
            'details'       => ['type' => 'string'],
            'newValue'      => ['type' => 'object'],
            'oldValue'      => ['type' => 'object'],
            'createDate'    => ['type' => 'string'],
            'user'          => ['type' => 'string']
        ],
        'additionalProperties' => true,
    ];
}
