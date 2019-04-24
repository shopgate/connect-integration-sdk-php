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

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Services\OER\ValueObject;
use function GuzzleHttp\Psr7\stream_for;

class Catalog implements EntityInterface
{
    use EntityTrait;

    /**
     * @todo-sg: move this to some sort of default config loader, can probably use an .env module handler as well
     * @see    https://packagist.org/packages/vlucas/phpdotenv
     */
    const BASE_URI      = 'https://shopgate.com';
    const VERSION       = '/v1';
    const ENDPOINT_PATH = '/merchants/{merchantCode}/events';

    /** Entity type */
    const ENTITY = 'catalog';

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

        //@todo-sg: allow to pass these from meta?, but base uri should be part of the initial config
        $request = new Request(
            'POST',
            new Uri(self::BASE_URI . self::VERSION . self::ENDPOINT_PATH)
        );
        $request->withBody(stream_for(http_build_query($updateEvent->toArray())));

        return $this->client->request($request); //todo-sg: may need to handle response in a special way?
    }
}
