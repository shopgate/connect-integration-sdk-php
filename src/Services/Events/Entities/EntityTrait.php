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
use Shopgate\ConnectSdk\Services\Events\DTO\Payload\Factory as PayloadFactory;

trait EntityTrait
{
    /**
     * @todo-sg: may need to think about logging/debugging requests and returning data for mage to log?
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    //todo-sg: may need this for multi item payloads
    //    protected function parse2d($data)
    //    {
    //        if ($this->is2d($data)) {
    //            foreach ($data as $body) {
    //                $this->addEvent($updateEvent, $entityId, $body);
    //            }
    //        } else {
    //            $this->addEvent($updateEvent, $entityId, $data);
    //        }
    //    }

    /**
     * @param EventFactory $updateEvent
     * @param string       $entityId
     * @param array        $body
     *
     * @throws InvalidDataTypeException
     */
    private function addEvent(EventFactory $updateEvent, $entityId, $body)
    {
        $payload = (new PayloadFactory())->catalog->updateCategory($body);
        $updateEvent->addUpdateEvent($entityId, self::ENTITY, $payload);
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    //    private function is2d(array $array)
    //    {
    //        return count($array) !== count($array, COUNT_RECURSIVE);
    //    }
}
