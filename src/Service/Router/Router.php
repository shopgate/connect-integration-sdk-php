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

namespace Shopgate\CloudIntegrationSdk\Service\Router;

use Shopgate\CloudIntegrationSdk\Repository;
use Shopgate\CloudIntegrationSdk\Service\Authenticator;
use Shopgate\CloudIntegrationSdk\Service\RequestHandler;
use Shopgate\CloudIntegrationSdk\Service\UriParser;
use Shopgate\CloudIntegrationSdk\ValueObject\Request;
use Shopgate\CloudIntegrationSdk\ValueObject\Response;
use Shopgate\CloudIntegrationSdk\ValueObject\Route;
use Shopgate\CloudIntegrationSdk\ValueObject\RequestMethod;

class Router
{
    /** @var RequestHandler\RequestHandlerInterface[][] */
    private $requestHandlers;

    /** @var UriParser\UriParser */
    private $uriParser;

    /**
     * @param Repository\AbstractClientCredentials $clientCredentialsRepository
     * @param Repository\AbstractToken             $tokenRepository
     * @param Repository\AbstractUser              $userRepository
     * @param Repository\AbstractPathInfo          $pathInfoRepository
     *
     * @throws \InvalidArgumentException
     * @throws UriParser\Exception\InvalidRoute
     */
    public function __construct(
        Repository\AbstractClientCredentials $clientCredentialsRepository,
        Repository\AbstractToken $tokenRepository,
        Repository\AbstractUser $userRepository,
        Repository\AbstractPathInfo $pathInfoRepository
    ) {
        $this->uriParser = new UriParser\UriParser();

        // add predefined routes
        $this->subscribe(new Route\AuthToken(), new RequestMethod\Get(),
            new RequestHandler\PostAuthToken(
                $clientCredentialsRepository, $tokenRepository, $userRepository
            )
        );
        $this->subscribe(new Route\V2(), new RequestMethod\Get(),
            new RequestHandler\GetV2($clientCredentialsRepository, $pathInfoRepository)
        );
    }

    /**
     * @param Route\AbstractRoute                    $route
     * @param RequestMethod\AbstractRequestMethod    $method
     * @param RequestHandler\RequestHandlerInterface $handler
     *
     * @throws \InvalidArgumentException
     * @throws UriParser\Exception\InvalidRoute
     */
    public function subscribe(
        Route\AbstractRoute $route,
        RequestMethod\AbstractRequestMethod $method,
        RequestHandler\RequestHandlerInterface $handler
    ) {
        if (null === $route) {
            throw new \InvalidArgumentException("Argument '\$route' is invalid!");
        }
        if (null === $method) {
            throw new \InvalidArgumentException("Argument '\$method' is invalid!");
        }
        if (null === $handler) {
            throw new \InvalidArgumentException("Argument '\$handler' is invalid!");
        }

        // the parser needs all subscribed routes to be able to parse the requested uri strings
        $this->uriParser->addRoute($route);

        // do the actual subscription by assigning a request handler to the given route and method
        if (!array_key_exists($route->getIdentifier(), $this->requestHandlers)) {
            $this->requestHandlers[$route->getIdentifier()] = array();
        }
        $this->requestHandlers[$route->getIdentifier()][(string) $method] = $handler;
    }

    /**
     * @param Request\Request $request
     *
     * @return Response
     *
     * @throws Authenticator\Exception\Unauthorized
     * @throws Exception\UnregisteredRoute
     * @throws Exception\UnregisteredRouteMethod
     * @throws RequestHandler\Exception\InvalidRequestHandler
     * @throws UriParser\Exception\InvalidRoute
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function dispatch(Request\Request $request)
    {
        // get the route from the uri parser by passing the uri string
        $route = $this->uriParser->getRoute($request->getUri());
        if (empty($route)) {
            throw new UriParser\Exception\InvalidRoute();
        }

        // check if the route and method was subscribed before
        if (!array_key_exists($route->getIdentifier(), $this->requestHandlers)) {
            throw new Exception\UnregisteredRoute($route->getIdentifier());
        }
        if (empty($this->requestHandlers[$route->getIdentifier()][(string) $request->getMethod()])) {
            throw new Exception\UnregisteredRouteMethod((string) $request->getMethod(), $route->getIdentifier());
        }

        // get request handler that a route -method combination subscribed
        $requestHandler = $this->requestHandlers[$route->getIdentifier()][(string) $request->getMethod()];
        if (empty($requestHandler)) {
            throw new RequestHandler\Exception\InvalidRequestHandler();
        }

        // check if a valid authenticator was provided and authenticate the request
        $auth = $requestHandler->getAuthenticator();
        if (!($auth instanceof Authenticator\AuthenticatorInterface)) {
            throw new Authenticator\Exception\Unauthorized();
        }
        $auth->authenticate($request);

        // finally handle the request using the request handler, subscribed by the rout+method combination
        return $requestHandler->handle($request);
    }
}
