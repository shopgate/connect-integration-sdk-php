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
use Shopgate\ConnectSdk\Dto\Async\Factory;
use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\DtoObject;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\Client\GrantType\ShopgateCredentials;
use Shopgate\ConnectSdk\Http\Persistence\EncryptedFile;
use Shopgate\ConnectSdk\Http\Persistence\PersistenceChain;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\ShopgateSdk;
use function json_decode;

class Client implements ClientInterface
{
    /** @var GuzzleClientInterface */
    private $client;

    /** @var string */
    private $baseUri;

    /** @var string */
    private $merchantCode;

    /** @var OAuth2Middleware */
    private $OAuthMiddleware;

    /**
     * @param GuzzleClientInterface $client
     * @param OAuth2Middleware      $OAuth2Middleware
     * @param string                $baseUri
     * @param string                $merchantCode
     */
    public function __construct(
        GuzzleClientInterface $client,
        OAuth2Middleware $OAuth2Middleware,
        $baseUri,
        $merchantCode
    ) {
        $this->client = $client;
        $this->baseUri = rtrim($baseUri, '/');
        $this->merchantCode = $merchantCode;
        $this->OAuthMiddleware = $OAuth2Middleware;
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
            $accessTokenPath = __DIR__ . ($env !== '' ? '/../access_token_' . $env : '/../access_token.txt');
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
            new EncryptedFile(new Json(), $accessTokenPath, $clientSecret)
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
    public function buildServiceUrl($serviceName, $path = '')
    {
        return str_replace('{service}', $serviceName, $this->baseUri)
            . '/v1'
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
        $handlerStack->push($this->OAuthMiddleware, 'oauth');
    }

    protected function removeOAuthAuthentication()
    {
        /** @var HandlerStack $handlerStack */
        $handlerStack = $this->client->getConfig('handler');
        $handlerStack->remove('oauth');
    }

    /**
     * @param array $params ['url' => .., 'query' => .., 'body' => .., 'method' => .., 'service' => .., 'path' => ..]
     *                      parameter 'body' or 'json' can not be set both at a time
     *                      if parameter 'url' is set the oauth authentication will be deactivated
     *
     * @return ResponseInterface|array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws TokenPersistenceException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function doRequest(array $params)
    {
        $this->removeOAuthAuthentication();

        if (isset($params['query']['requestType'])) {
            unset($params['query']['requestType']);
        }

        $parameters = [];
        if (isset($params['query'])) {
            $parameters['query'] = $this->fixBoolValuesInQuery($params['query']);
        }

        if (!isset($params['url'])) {
            $this->addOAuthAuthentication();
        }

        if (!$this->isDirect($params)) {
            return $this->triggerEvent($params);
        }

        $response = null;
        $defaultConfigs = [
            'connect_timeout' => 5.0
        ];
        try {
            if (isset($params['body'])) {
                $parameters['body'] = $params['body'];
            } elseif (isset($params['json'])) {
                $json = !empty($params['json']) ? $params['json'] : [];
                $parameters['json'] = $json instanceof Base ? $json->toJson() : (new DtoObject($json))->toJson();
            }

            $response = $this->client->request(
                $params['method'],
                isset($params['url']) ? $params['url'] : $this->buildServiceUrl($params['service'], $params['path']),
                array_merge($defaultConfigs, $parameters)
            );
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
        } catch (TokenPersistenceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }

        if ($response instanceof ResponseInterface) {
            $this->checkForErrorsInResponse($response);
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws NotFoundException
     * @throws RequestException
     */
    private function checkForErrorsInResponse($response)
    {
        if ($body = $response->getBody()) {
            $responseContent = json_decode((string)$body, true);

            if (!isset($responseContent['errors']) || empty($responseContent['errors'])) {
                return;
            }

            foreach ($responseContent['errors'] as $error) {
                if ($error['code'] === 404) {
                    throw new NotFoundException(
                        $error['reason']
                    );
                }

                throw new RequestException(
                    $error['code'],
                    $error['reason']
                );
            }
        }
    }

    /**
     * This method will convert true (bool) values to 'true' (string) and false (bool) to 'false' (string).
     *
     * @param array $queryParameters
     *
     * @return array
     */
    private function fixBoolValuesInQuery($queryParameters)
    {
        foreach ($queryParameters as $queryParameterKey => $queryParameterValue) {
            if (!is_bool($queryParameterValue)) {
                continue;
            }
            $queryParameters[$queryParameterKey] = !empty($queryParameterValue) ? 'true' : 'false';
        }

        return $queryParameters;
    }

    /**
     * @param array $params
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    private function triggerEvent(array $params)
    {
        $values = [
            isset($params['json']) ? $params['json'] : new DtoObject([], [
                'type' => 'object',
                'additionalProperties' => true
            ]),
        ];
        if ($params['action'] === 'create') {
            $key = array_keys($params['json'])[0];
            $values = $params['json'][$key];
        }

        $factory = new Factory();
        foreach ($values as $payload) {
            $payload = $this->prepareEventPayload($params, $payload);

            $entityId = isset($params['entityId']) ? $params['entityId'] : null;
            $factory->addEvent($params['action'], $params['entity'], $payload, $entityId);
        }

        try {
            return $this->client->request(
                'post',
                $this->buildServiceUrl('event-receiver', 'events'),
                [
                    'json' => $factory->getRequest()->toJson(),
                    'http_errors' => false,
                    'connect_timeout' => 5.0
                ]
            );
        } catch (GuzzleRequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            throw new RequestException(
                $statusCode,
                $e->getResponse() && $e->getResponse()->getBody() ? $e->getResponse()->getBody()->getContents()
                    : $e->getMessage()
            );
        } catch (AccessTokenRequestException $e) {
            throw new AuthenticationInvalidException($e->getMessage());
        } catch (GuzzleException $e) {
            throw new UnknownException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function isDirect(array $params)
    {
        return
            (!isset($params['requestType']) && $params['method'] === 'get')
            || $params['requestType'] === ShopgateSdk::REQUEST_TYPE_DIRECT;
    }

    /**
     * @param array $params
     * @param Base  $payload
     *
     * @return Base
     */
    private function prepareEventPayload(array $params, Base $payload)
    {
        if (isset($params['query']['catalogCode'])) {
            $payload->setCatalogCode($params['query']['catalogCode']);
        }

        return $payload;
    }
}
