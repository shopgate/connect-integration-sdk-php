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

namespace Shopgate\ConnectSdk\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;

interface ClientInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $template
     */
    public function enableRequestLogging(LoggerInterface $logger = null, $template = '');

    /**
     * Sends a request to the API of a given service or to the URL provided.
     *
     * Using a custom URL (option "url") will remove all oAuth authentication from the request. Setting the "url"
     * option overrides the "service" option but will append the "path", if set.
     *
     * <code>$options = [
     *   'service'     => 'string',                       // the name of the service to be requested
     *   'method'      => 'string',                       // HTTP method, defaults to 'get'
     *   'version'     => 'string',                       // API version, defaults to 'v1'
     *   'path'        => 'string',                       // the route to call, omitting the 'v1/merchants/.../' part
     *   'json'        => 'boolean',                      // JSON en-/decode request/response, defaults to true
     *   'body'        => 'array|stdClass|string',        // the request body, defaults to null
     *   'query'       => 'array|stdClass',               // the query part of the URL, defaults to []
     *   'url'         => 'string',                       // use a custom URL to send the request to
     * ]</code>
     *
     * @param array{service: string, method: string, version: string, path: string, json: bool, filters: array|\stdClass, requestType: string, body: array|\stdClass|string, query: array|\stdClass, url: string} $options
     *
     * @return ResponseInterface|array
     *
     * @throws AuthenticationInvalidException
     * @throws UnknownException
     * @throws NotFoundException
     * @throws RequestException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     */
    public function request(array $options);

    /**
     * Publishes events of one type (same entity, same action) to the event-receiver service.
     *
     * @param string $eventName One of "entityCreated", "entityUpdated", "entityDeleted".
     * @param string $entityName The name of the entity, e.g. "order", "product", "attribute", ...
     * @param array[]|\stdClass[] $entities The actual entities to be created, updated or deleted
     * @param string|null $entityIdPropertyName The name of the property that contains an entity's ID (usually only for "update", "delete")
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws UnknownException
     * @throws NotFoundException
     * @throws RequestException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     */
    public function publish($eventName, $entityName, $entities, $entityIdPropertyName = null);

    /**
     * Publishes one "entityDeleted" event.
     *
     * @param string $entityName The name of the entity, e.g. "order", "product", "attribute", ...
     * @param string $entityId The ID of the entity to be updated
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws UnknownException
     * @throws NotFoundException
     * @throws RequestException
     * @throws InvalidDataTypeException
     * @throws TokenPersistenceException
     */
    public function publishEntityDeleted($entityName, $entityId);
}
