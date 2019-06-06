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

use Dto\Exceptions\InvalidDataTypeException;
use Exception;
use Shopgate\ConnectSdk\DTO\Base as Payload;
use Shopgate\ConnectSdk\Exceptions\TypeNoExistException;

class Factory
{
    const EVENT_UPDATE = 'entityUpdated';
    const EVENT_CREATE = 'entityCreated';
    const EVENT_DELETE = 'entityDeleted';

    /** @var array - DTO entity event type map */
    protected $typeMap = [
        'update' => self::EVENT_UPDATE,
        'create' => self::EVENT_CREATE,
        'delete' => self::EVENT_DELETE,
    ];

    /**
     * @var Request
     */
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
     * @param string       $entityId
     * @param string       $entity
     * @param Payload|null $payload
     *
     * @return Factory
     * @throws InvalidDataTypeException
     * @throws Exception
     */
    public function addEvent($type, $entityId, $entity, Payload $payload = null)
    {
        $eventType = $this->translateType($type);
        $event     = new Event();
        $event->setEvent($eventType)
              ->setEntity($entity)
              ->setEntityId($entityId)
              ->setPayload($payload ? : new Payload());
        $this->request->getEvents()->append($event);

        return $this;
    }

    /**
     * @param string $type
     *
     * @return string
     * @throws Exception
     */
    private function translateType($type)
    {
        if (isset($this->typeMap[$type])) {
            return $this->typeMap[$type];
        }
        throw new TypeNoExistException('Incorrect type provided');
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
