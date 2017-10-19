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

namespace Shopgate\CloudIntegrationSdk\ValueObject\Request;

class Request
{
    // TODO: Implement methods and add PHPDoc

    public function getUri()
    {
        // TODO: Implementation
        return "";
    }

    public function getHeaders()
    {
        // TODO: Implementation
        return [];
    }

    public function getMethod()
    {
        // TODO: Implementation
        return "";
    }

    /**
     * @return bool|string
     */
    public function getBody()
    {
        return file_get_contents('php://input');
    }

    /**
     * @param Request $request
     * @param string  $paramName
     *
     * @return string | null
     *
     * @throws Exception\BadRequest
     */
    public function parseParam($paramName)
    {
        $data = [];
        parse_str(parse_url($this->getUri(), PHP_URL_QUERY), $data);

        // parse either from request query or from request body
        if (!empty($data[$paramName])) {
            return $data[$paramName];
        } else {
            $contentTypeKey = 'Content-Type';
            $requestHeaders = $this->getHeaders();

            // check if there is a content type header provided
            if (empty($requestHeaders[$contentTypeKey])) {
                throw new Exception\BadRequest('Invalid request body.');
            }

            // check if the provided content type is supported
            switch($this->parseContentType($requestHeaders[$contentTypeKey])) {
                case 'application/json':
                    $data = json_decode($this->getBody(), true);
                    if (!empty($data[$paramName])) {
                        return $data[$paramName];
                    }
                    break;
                case 'application/x-www-form-urlencoded':
                    parse_str($this->getBody(), $data);
                    if (!empty($data[$paramName])) {
                        return $data[$paramName];
                    }
                    break;
                default:
                    throw new Exception\BadRequest('Unsupported Content-Type provided.');
            }
        }

        return null;
    }

    /**
     * @param string $contentType
     *
     * @return string
     */
    private function parseContentType($contentType) {
        // the content type is always the first part of to possible ones, delimited by semicolon
        $parts = explode(";", trim($contentType));
        return trim($parts[0]);
    }
}
