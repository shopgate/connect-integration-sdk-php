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

namespace Shopgate\ConnectSdk\Dto\Async;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method string getEvent()
 * @method Event setEvent(string $event)
 * @method string getEntity()
 * @method Event setEntity(string $entity)
 * @method string getEntityId()
 * @method Event setEntityId(string $entityId)
 * @method Event setPayload(Base $payload)
 */
class Event extends Base
{
    const UPDATE = 'entityUpdated';
    const CREATE = 'entityCreated';
    const DELETE = 'entityDeleted';

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getDefaultSchema()
    {
        return [
            'type' => 'object',
            'properties' => [
                'event' => [
                    'type' => 'string',
                    'enum' => [self::UPDATE, self::CREATE, self::DELETE]
                ],
                'entity' => ['type' => 'string'],
                'entityId' => ['type' => 'string'],
                'payload' => ['type' => 'object']
            ],
            'additionalProperties' => true
        ];
    }
}
