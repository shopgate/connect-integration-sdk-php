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
    /** @var Route\AbstractRoute[] */
    private $routes;

    public function __construct() {
        $this->routes = array();
    }

    /**
     * @param Route\AbstractRoute $route
     *
     * @throws Exception\InvalidRoute
     */
    public function addRoute(Route\AbstractRoute $route) {
        if (empty($route)) {
            throw new Exception\InvalidRoute();
        }

        $this->routes[$route->getIdentifier()] = $route;
    }

    /**
     * @param string $uriString
     *
     * @return Route\AbstractRoute | null
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRoute($uriString)
    {
        foreach ($this->routes as $route) {
            $match = $this->matchRouteUri($route->getPattern(), $this->siplifyUriString($uriString));
            if (!empty($match)) {
                return $route;
            }
        }

        // no matching route found in list
        return null;
    }

    /**
     * Returns the list of parameters in defined order, that the given route takes and the given uri string contains
     *
     * @param Route\AbstractRoute $route
     * @param string $uriString
     *
     * @return string[]
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRouteParams(Route\AbstractRoute $route, $uriString) {
        $match = $this->matchRouteUri($route->getPattern(), $this->siplifyUriString($uriString));

        // read only named elements, that are furthermore called "params"
        $result = array();
        foreach ($route->getParamNameList() as $paramName) {
            $result[(string) $paramName] = $match[(string) $paramName];
        }

        return $result;
    }

    /**
     * Simplifies pattern matching by only using the actual path string (plus squash left slashes for easier patterns)
     *
     * @param string $uriString
     *
     * @return string
     */
    private function siplifyUriString($uriString) {
        return '/' . ltrim(parse_url($uriString, PHP_URL_PATH), '/');
    }

    /**
     * Result will be empty if there was no match. Contains all parsed elements otherwise.
     *
     * @param string $pattern
     * @param string $uriString
     *
     * @return string[]
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function matchRouteUri($pattern, $uriString)
    {
        if (empty($pattern)) {
            throw new \InvalidArgumentException("Invalid argument '\$pattern' .");
        }

        if (empty($uriString)) {
            throw new \InvalidArgumentException("Invalid argument '\$uriString'.");
        }

        $matchResult = preg_match($pattern, $uriString, $matches);
        if (false === $matchResult) {
            throw new \RuntimeException('Unexpected failure while parsing a given uri string.');
        }

        return $matches;
    }
}
