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

namespace Shopgate\ConnectSdk\DTO\Async;

use Dto\Exceptions\InvalidDataTypeException;
use Shopgate\ConnectSdk\DTO\Base as Payload;

class Factory
{
    /** @var Request */
    protected $request;

    /**
     * Initialize events
     */
    public function __construct()
    {
        $this->request = (new Request())->setEvents([]);
    }

    /**
     * @param string       $type
     * @param string       $entity
     * @param Payload|null $payload
     * @param string|null  $entityId
     *
     * @return Factory
     * @throws InvalidDataTypeException
     */
    public function addEvent($type, $entity, Payload $payload = null, $entityId = null)
    {
        $event = new Event();
        $event->setEvent('entity' . ucfirst($type) . 'd')
              ->setEntity($entity)
              ->setPayload($payload ? : new Payload());
        if ($entityId) {
            $event->setEntityId($entityId);
        }
        $this->request->getEvents()->append($event);

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
