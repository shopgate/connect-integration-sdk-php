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

namespace Shopgate\ConnectSdk\Services\Events\DTO\Async;

use Shopgate\ConnectSdk\Services\Events\DTO\Base;
use Shopgate\ConnectSdk\Services\Events\DTO\Payloads\Payload;
use Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category\Async as Category;

/**
 * @method string getEvent()
 * @method Event setEvent(string $event)
 * @method string getEntity()
 * @method Event setEntity(string $entity)
 * @method string getEntityId()
 * @method Event setEntityId(string $entityId)
 * @method Event setPayload(Payload $payload)
 */
class Event extends Base
{
    /**
     * @return array
     */
    public function getDefaultSchema()
    {
        return [
            'type'                 => 'object',
            'properties'           => [
                'event'    => [
                    'type' => 'string',
                    'enum' => [Update::EVENT/*, Delete:EVENT, Create::EVENT*/]
                ],
                'entity'   => [
                    'type' => 'string',
                    'enum' => [Category::ENTITY]
                ],
                'entityId' => ['type' => 'string'],
                'payload'  => ['type' => Payload::class]
            ],
            'additionalProperties' => false
        ];
    }
}
