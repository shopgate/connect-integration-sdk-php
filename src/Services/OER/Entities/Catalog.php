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

namespace Shopgate\ConnectSdk\Services\OER\Entities;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Services\OER\ValueObject;
use function GuzzleHttp\Psr7\stream_for;

class Catalog implements EntityInterface
{
    use EntityTrait;

    const ENTITY       = 'catalog';
    const PATH_SERVICE = 'category';
    const PATH_EVENTS  = 'events';

    /**
     * @param string $entityId
     * @param array  $data
     * @param array  $meta
     *
     * @return ResponseInterface
     */
    public function update($entityId, $data = [], $meta = [])
    {
        $updateEvent = new ValueObject\EntityUpdate(
            self::ENTITY,
            $entityId,
            $data
        );

        //@todo-sg: body is different based on async or not, need two different DTOs
        $request = [
                'body'    => stream_for(http_build_query($updateEvent->toArray())),
                'service' => $this->isAsync($meta) ? self::PATH_EVENTS : self::PATH_SERVICE
            ] + $meta;

        //todo-sg: mark an exception thrown here possibly, implementer needs to handle
        return $this->client->request('post', '/', $request);
    }

    /**
     * @todo-sg: implement logic to call `events` endpoint on non-direct
     *
     * @param array $config
     *
     * @return bool
     */
    private function isAsync(array $config)
    {
        //todo-sg: constant if needed, may need to rethink
        return !isset($config['requestType']) || $config['requestType'] !== 'direct';
    }
}
