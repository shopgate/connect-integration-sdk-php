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
use Shopgate\ConnectSdk\Services\Events\DTO\Payload\Factory as PayloadFactory;
use Shopgate\ConnectSdk\Services\Events\Entities;

class Async implements Entities\EntityInterface
{
    use Entities\EntityTrait;

    /** @var string - needs to be implemented for every class */
    const ENTITY = 'category';

    /**
     * @inheritDoc
     *
     * @used-by \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog::__call()
     * @throws InvalidDataTypeException
     */
    public function update($entityId, $data = [], $meta = [])
    {
        $payload = (new PayloadFactory())->catalog->updateCategory($data);
        $factory = $this->addEvent(Entities\EntityInterface::EVENT_TYPE_UPDATE, $entityId, $payload);

        return $this->client->request(
            'post',
            'events',
            ['json' => $factory->getRequest()->toJson(), 'query' => $meta]
        );
    }

    /**
     * @inheritDoc
     *
     * @used-by \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog::__call()
     * @throws InvalidDataTypeException
     */
    public function create($data = [], $meta = [])
    {
        $payload = (new PayloadFactory())->catalog->createCategory($data);
        $factory = $this->addEvent(Entities\EntityInterface::EVENT_TYPE_CREATE, '', $payload);

        return $this->client->request(
            'post',
            'events',
            ['json' => $factory->getRequest()->toJson(), 'query' => $meta]
        );
    }

    /**
     * @inheritDoc
     *
     * @used-by \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog::__call()
     * @throws InvalidDataTypeException
     */
    public function delete($entityId, $meta = [])
    {
        $factory = $this->addEvent(Entities\EntityInterface::EVENT_TYPE_DELETE, $entityId);

        return $this->client->request(
            'post',
            'events',
            ['json' => $factory->getRequest()->toJson(), 'query' => $meta]
        );
    }
}
