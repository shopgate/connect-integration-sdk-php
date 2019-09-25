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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto;

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Object as dtoObject;

/**
 * @method HistoryItem setId(integer $id)
 * @method HistoryItem setEventName(string $eventName)
 * @method HistoryItem setEventDetails(string $eventDetails)
 * @method HistoryItem setEventNewValue(string $eventNewValue)
 * @method HistoryItem setEventOldValue(string $eventOldValue)
 * @method HistoryItem setEventDateTime(string $eventDateTime)
 * @method HistoryItem setEventUser(string $eventUser)
 * @method integer getId()
 * @method string getEventName()
 * @method string getEventDetails()
 * @method dtoObject getEventNewValue()
 * @method string getEventOldValue()
 * @method string getEventDateTime()
 * @method string getEventUser()
 *
 * @codeCoverageIgnore
 */
class HistoryItem extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'id'            => ['type' => 'number'],
            'eventName'     => ['type' => 'string'],
            'eventDetails'  => ['type' => 'string'],
            'eventNewValue' => ['type' => 'object'],
            'eventOldValue' => ['type' => 'string'],
            'eventDateTime' => ['type' => 'string'],
            'eventUser'     => ['type' => 'string']
        ],
        'additionalProperties' => true,
    ];
}
