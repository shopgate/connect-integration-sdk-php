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

namespace Shopgate\ConnectSdk\Service;

use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\Exception\RequestException as RequestExceptionAlias;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\File;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\Stream;

class BulkImport
{
    const NAME = 'import';

    /** @var ClientInterface */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $source
     *
     * @return string
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestExceptionAlias
     * @throws UnknownException
     * @throws TokenPersistenceException
     */
    protected function createImport($source = '')
    {
        $response = $this->client->request([
            'service' => self::NAME,
            'method' => 'post',
            'path' => 'imports',
            'body' => ['source' => $source]
        ]);

        return isset($response['ref']) ? $response['ref'] : null;
    }

    /**
     * @return File
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestExceptionAlias
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function createFileImport()
    {
        return new File($this->client, $this->createImport());
    }

    /**
     * @return Stream
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestExceptionAlias
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    public function createStreamImport()
    {
        return new Stream($this->client, $this->createImport());
    }
}
