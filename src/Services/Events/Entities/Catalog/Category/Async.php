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

namespace Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category;

use Dto\Exceptions\InvalidDataTypeException;
use Exception;
use GuzzleHttp\Psr7\Request;
use Shopgate\ConnectSdk\Services\Events\DTO\Async\Update;
use Shopgate\ConnectSdk\Services\Events\DTO\Payloads\Catalog\Category;
use Shopgate\ConnectSdk\Services\Events\Entities;
use function GuzzleHttp\Psr7\stream_for;

class Async implements Entities\EntityInterface
{
    use Entities\EntityTrait;

    const ENTITY = 'category';

    /**
     * @inheritDoc
     *
     * @used-by \Shopgate\ConnectSdk\Services\Events\Connector\Catalog::__call()
     * @throws InvalidDataTypeException
     * @throws Exception
     */
    public function update($entityId, $data = [], $meta = [])
    {
        $payloadClass = $this->getPayload();
        if (!class_exists($payloadClass)) {
            throw new Exception('Error instantiating class');
        }
        /** @var Category $payload */
        $payload     = new $payloadClass();
        $updateEvent = new Update();
        $event       = $updateEvent->createEvent($entityId, self::ENTITY, $payload->setData($data));

        //@todo-sg: body is different based on async or not, need two different DTOs
        $request = new Request('POST', '/');
        $request->withBody(stream_for(http_build_query($event->toArray())));

        //todo-sg: mark an exception thrown here possibly, implementer needs to handle
        return $this->client->send($request, $meta);
    }

    /**
     * @return string
     */
    protected function getPayload()
    {
        return str_replace('Entities', 'DTO\Payloads', __NAMESPACE__);
    }
}
