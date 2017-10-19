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

namespace Shopgate\CloudIntegrationSdk\Service\UriParser;

use Shopgate\CloudIntegrationSdk\ValueObject\Route;

class UriParser
{
    /** @var Route\AbstractRoute */
    private $routes;

    public function __construct() {
        $this->routes = [];
    }

    /**
     * @param Route\AbstractRoute $route
     *
     * @throws Exception\InvalidRoute
     */
    public function addRoute(Route\AbstractRoute $route) {
        // TODO: Finish implementation
        if (empty($route)) {
            throw new Exception\InvalidRoute();
        }
        $routes[$route->getIdentifier()] = $route;
    }

    /**
     * @param string $uriString
     *
     * @return Route\AbstractRoute | null
     */
    public function getRoute($uriString)
    {
        /** @var Route\AbstractRoute $route */
        $route = null;
        foreach ($this->routes as $route) {
            if ($this->matchRouteUri($route->getPattern(), $uriString)) {
                return $route;
            }
        }

        // no matching route found in list
        return null;
    }

    /**
     * @param string $pattern
     * @param string $uriString
     *
     * @return bool
     */
    private function matchRouteUri($pattern, $uriString)
    {
        if (empty($pattern)) {
            throw new \InvalidArgumentException("Invalid argument '\$pattern' .");
        }

        if (empty($uriString)) {
            throw new \InvalidArgumentException("Invalid argument '\$uriString'.");
        }

        $matchResult = preg_match($pattern, $uriString);
        if (false === $matchResult) {
            throw new \RuntimeException("Unexpected failure while parsing a given uri string.");
        }

        return !!$matchResult;
    }
}
