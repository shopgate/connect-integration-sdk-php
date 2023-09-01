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

use Exception;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use kamermans\OAuth2\Exception\AccessTokenRequestException;
use kamermans\OAuth2\OAuth2Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Helper\Value;
use Shopgate\ConnectSdk\Http\Client\GrantType\ShopgateCredentials;
use Shopgate\ConnectSdk\Http\Persistence\EncryptedFile;
use Shopgate\ConnectSdk\Http\Persistence\PersistenceChain;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;

class Client implements ClientInterface
{
    /** @var GuzzleClientInterface */
    private $client;

    /** @var string */
    private $baseUri;

    /** @var string */
    private $merchantCode;

    /** @var OAuth2Middleware */
    private $oAuthMiddleware;

    /** @var string[] */
    private $eventsWithId;

    /** @var string[] */
    private $eventsWithoutPayload;

    /**
     * @param GuzzleClientInterface $client
     * @param OAuth2Middleware $oAuth2Middleware
     * @param string $baseUri
     * @param string $merchantCode
     * @param string[] $eventsWithId
     * @param string[] $eventsWithotPayload
     */
    public function __construct(
        GuzzleClientInterface $client,
        OAuth2Middleware      $oAuth2Middleware,
                              $baseUri,
                              $merchantCode,
                              $eventsWithId = ['entityUpdated', 'entityDeleted'],
                              $eventsWithotPayload = ['entityDeleted']
    ) {
        $this->client = $client;
        $this->baseUri = rtrim($baseUri, '/');
        $this->merchantCode = $merchantCode;
        $this->oAuthMiddleware = $oAuth2Middleware;
        $this->eventsWithId = $eventsWithId;
        $this->eventsWithoutPayload = $eventsWithotPayload;
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $merchantCode
     * @param string $username
     * @param string $password
     * @param string $baseUri
     * @param string $env
     * @param string $accessTokenPath
     *
     * @return Client
     */
    public static function createInstance(
        $clientId,
        $clientSecret,
        $merchantCode,
        $username,
        $password,
        $baseUri = '',
        $env = '',
        $accessTokenPath = ''
    ) {
        $env = $env === 'production' ? '' : $env;

        if (empty($baseUri)) {
            $baseUri = str_replace('{env}', $env, 'https://{service}.shopgate{env}.io');
        }

        if (empty($accessTokenPath)) {
            $envSuffix = $env ?: 'production';
            $suffix = md5("{$envSuffix}-{$clientId}-{$clientSecret}-{$merchantCode}-{$username}-{$password}");
            $accessTokenPath =  __DIR__ . "/../access_token-{$suffix}.txt";
        }

        $reAuthClient = new \GuzzleHttp\Client(
            [
                'base_uri' => rtrim(str_replace('{service}', 'auth', $baseUri), '/') . '/oauth/token'
            ]
        );

        $OAuthMiddleware = new OAuth2Middleware(
            new ShopgateCredentials(
                $reAuthClient,
                [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'merchant_code' => $merchantCode,
                    'username' => $username,
                    'password' => $password
                ]
            )
        );

        $OAuthMiddleware->setTokenPersistence(new PersistenceChain([
            new EncryptedFile($accessTokenPath, $clientSecret)
        ]));

        $handlerStack = HandlerStack::create();
        $client = new \GuzzleHttp\Client(
            [
                'auth' => 'oauth',
                'handler' => $handlerStack
            ]
        );

        return new self($client, $OAuthMiddleware, $baseUri, $merchantCode);
    }

    /**
     * @return GuzzleClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param LoggerInterface $logger
     * @param string          $template
     *
     * @throws Exception
     */
    public function enableRequestLogging(LoggerInterface $logger = null, $template = '')
    {
        $handler = $this->client->getConfig('handler');

        if (!$logger) {
            $logger = new Logger('request_logger', [new StreamHandler('php://out')]);
        }

        if (!$template) {
            $template = 'URL: {url} Method: {method} RequestBody: {req_body} ResponseBody: {res_body}';
        }

        $handler->push(Middleware::log($logger, new MessageFormatter($template)));
    }

    /**
     * @param string $serviceName
     * @param string $path
     *
     * @return string
     */
    public function buildServiceUrl($serviceName, $path = '', $version = 'v1')
    {
        return str_replace('{service}', $serviceName, $this->baseUri)
            . "/{$version}"
            . "/merchants/{$this->merchantCode}"
            . '/' . ltrim($path, '/');
    }

    /**
     * @param callable $middleware
     */
    public function addMiddleware(callable $middleware)
    {
        /** @var HandlerStack $handlerStack */
        $handlerStack = $this->client->getConfig('handler');
        $handlerStack->push($middleware);
    }

    protected function addOAuthAuthentication()
    {
        /** @var HandlerStack $handlerStack */
        $handlerStack = $this->client->getConfig('handler');
        $handlerStack->push($this->oAuthMiddleware, 'oauth');
    }

    protected function removeOAuthAuthentication()
    {
        /** @var HandlerStack $handlerStack */
        $handlerStack = $this->client->getConfig('handler');
        $handlerStack->remove('oauth');
    }

    public function request(array $options)
    {
        if (empty($options['service']) && empty($options['url'])) {
            throw new \ValueError('Option "service" or "url" must be set when sending a request');
        }

        // query parsing, conversion of bools to strings & JSON encode filters if set
        $query = Value::arrayBool2String((array)Value::elvis($options, 'query', []));
        if (!empty($query['filters']) && (is_array($query['filters']) || is_object($query['filters']))) {
            $query['filters'] = Json::encode($query['filters']);
        }

        // remove authentication on custom URLs (used for S3 uploads)
        if (!empty($options['url'])) {
            $this->removeOAuthAuthentication();
        } else {
            $this->addOAuthAuthentication();
        }

        $headers = [];
        $body = Value::elvis($options, 'body', null, 'isset', false);
        $json = Value::elvis($options, 'json', true, 'isset', false);

        if ($json && (is_array($body) || is_object($body))) {
            $headers['Content-Type'] = 'application/json';
            $body = Json::encode($body);
        }

        $httpClientOptions = [
            'connect_timeout' => 5.0,
            'body' => $body
        ];

        if (!empty($headers)) {
            $httpClientOptions['headers'] = $headers;
        }

        if (!empty($query)) {
            $httpClientOptions['query'] = $query;
        }

        $method = Value::elvis($options, 'method', 'get');
        $version = Value::elvis($options, 'version', 'v1');
        $path = Value::elvis($options, 'path', '');
        $url = Value::elvis($options, 'url', '');
        $uri = !empty($url) ? $url . $path : $this->buildServiceUrl($options['service'], $path, $version);

        $result = $this->send($method, $uri, $httpClientOptions)->getBody()->getContents();
        return $json && !empty($result) ? Json::decode($result) : $result;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws TokenPersistenceException might be thrown when oAuth2 middleware is active
     */
    private function send($method, $uri, $options)
    {
        try {
            return $this->client->request($method, $uri, $options);
        } catch (GuzzleRequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;

            if ($statusCode === 404) {
                throw new NotFoundException(
                    $e->getResponse() && $e->getResponse()->getBody() ? $e->getResponse()->getBody()->getContents()
                        : $e->getMessage()
                );
            }

            throw new RequestException(
                $statusCode,
                $e->getResponse() && $e->getResponse()->getBody() ? $e->getResponse()->getBody()->getContents()
                    : $e->getMessage()
            );
        } catch (GuzzleException $e) {
            throw new UnknownException($e->getMessage());
        } catch (AccessTokenRequestException $e) {
            throw new AuthenticationInvalidException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    public function publish($eventName, $entityName, $entities, $entityIdPropertyName = 'code')
    {
        $events = array_map(function ($entity) use ($eventName, $entityName, $entityIdPropertyName) {
            $entity = (array)$entity;

            $event = [
                'event' => $eventName,
                'entity' => $entityName,
                'payload' => in_array($eventName, $this->eventsWithoutPayload) ? [] : $entity
            ];

            // extract entityId from entity on events that require it
            if (in_array($eventName, $this->eventsWithId) && $entityIdPropertyName !== null) {
                $event['entityId'] = $entity[$entityIdPropertyName];
                unset($entity[$entityIdPropertyName]);
            }

            return $event;
        }, $entities);

        return $this->sendEvents($events);
    }

    public function publishEntityDeleted($entityName, $entityId)
    {
        return $this->sendEvents([[
            'event' => 'entityDeleted',
            'entity' => $entityName,
            'entityId' => $entityId,
            'payload' => new \stdClass()
        ]]);
    }

    /**
     * @param array[] $events
     *
     * @return ResponseInterface
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    private function sendEvents($events)
    {
        $this->addOAuthAuthentication();

        return $this->send(
            'post',
            $this->buildServiceUrl('event-receiver', 'events'),
            [
                'json' => ['events' => $events],
                'http_errors' => true,
                'connect_timeout' => 5.0
            ]
        );
    }
}
