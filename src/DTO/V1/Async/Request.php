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

namespace Shopgate\ConnectSdk\DTO\V1\Async;

use Shopgate\ConnectSdk\DTO\Base;

/**
 * @method Request setEvents(Event[] $events) - note, this rewrites the list. Use append if you want to add to the list.
 * @method Request getEvents()
 */
class Request extends Base
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'events' => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ]
        ],
        'additionalProperties' => false
    ];

    /**
     * This just makes sure that an empty object is
     * treated as a json object, not an array.
     *
     * @inheritDoc
     */
    public function toJson($pretty = false)
    {
        $json = parent::toJson();

        return str_replace('[]', '{}', $json);
    }
}
