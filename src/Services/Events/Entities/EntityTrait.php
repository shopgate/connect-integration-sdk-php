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

namespace Shopgate\ConnectSdk\Services\Events\Entities;

use Dto\Exceptions\InvalidDataTypeException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Services\Events\DTO\Async\Factory as EventFactory;
use Shopgate\ConnectSdk\Services\Events\DTO\Base as Payload;

trait EntityTrait
{
    /**
     * @var ClientInterface
     */
    protected $client;
    /** @var EventFactory */
    protected $eventFactory;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string       $type     - see EntityInterface constants
     * @param string       $entityId - id of the entity to update
     * @param Payload|null $payload
     *
     * @return EventFactory
     * @throws InvalidDataTypeException
     */
    protected function addEvent($type, $entityId, Payload $payload = null)
    {
        $eventFactory = $this->getEventFactory();
        $eventFactory->addEvent($type, $entityId, self::ENTITY, $payload);

        return $eventFactory;
    }

    /**
     * @return EventFactory
     */
    protected function getEventFactory()
    {
        if (null === $this->eventFactory) {
            $this->eventFactory = new EventFactory();
        }

        return $this->eventFactory;
    }
}
