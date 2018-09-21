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
    /** @var string */
    private $uri;

    /** @var string */
    private $method;

    /** @var string[] */
    private $headers;

    /** @var string */
    private $body;

    /**
     * @param string        $uri
     * @param string        $method
     * @param string[]      $headers
     * @param string | null $body
     */
    public function __construct($uri, $method, array $headers = array(), $body = null)
    {
        $this->uri = (string) $uri;
        $this->method = (string) $method;

        $this->headers = array();
        foreach ($headers as $key => $header) {
            $this->headers[(string) $key] = (string) $header;
        }

        // read body from php input stream, if no body was set
        $this->body = (string) $body;
        if (empty($body)) {
            $this->body = file_get_contents('php://input');
        }
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $headerKey
     *
     * @return string | null
     */
    public function getHeader($headerKey)
    {
        return (empty($this->headers[$headerKey])
            ? null
            : ((string) $this->headers[$headerKey])
        );
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string  $paramName
     *
     * @return string | null
     *
     * @throws Exception\BadRequest
     */
    public function getParam($paramName)
    {
        $data = array();
        parse_str(parse_url($this->getUri(), PHP_URL_QUERY), $data);

        // parse either from request query or from request body
        if (empty($data[$paramName])) {
            $contentTypeKey = 'Content-Type';
            $requestHeaders = $this->getHeaders();

            // check if there is a content type header provided
            if (empty($requestHeaders[$contentTypeKey])) {
                throw new Exception\BadRequest('Invalid request body.');
            }

            // check if the provided content type is supported
            switch ($this->parseContentType($requestHeaders[$contentTypeKey])) {
                case 'application/json':
                    $data = json_decode($this->getBody(), true);
                    break;
                case 'application/x-www-form-urlencoded':
                    parse_str($this->getBody(), $data);
                    break;
                default:
                    throw new Exception\BadRequest('Unsupported Content-Type provided.');
            }
        }

        return !empty($data[$paramName]) ? $data[$paramName] : null;
    }

    /**
     * @param string $contentType
     *
     * @return string
     */
    private function parseContentType($contentType)
    {
        // the content type is always the first part of to possible ones, delimited by semicolon
        $parts = explode(';', trim($contentType));
        return trim($parts[0]);
    }
}
