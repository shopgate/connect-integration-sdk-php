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

namespace Shopgate\CloudIntegrationSdk\ValueObject;

class Response
{
    const HTTP_OK                    = 200;
    const HTTP_BAD_REQUEST           = 400;
    const HTTP_UNAUTHORIZED          = 401;
    const HTTP_FORBIDDEN             = 403;
    const HTTP_NOT_FOUND             = 404;
    const HTTP_METHOD_NOT_ALLOWED    = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED       = 501;

    /**
     * Custom invalid codes
     */
    const UNREGISTERED_ROUTE        = 1001;
    const UNREGISTERED_ROUTE_METHOD = 1002;
    const INVALID_ROUTE             = 1010;
    const INVALID_REQUEST_HANDLER   = 1011;
    const INVALID_AUTHENTICATOR     = 1012;

    /** @var int */
    private $code;

    /** @var string[] */
    private $headers;

    /** @var string */
    private $body;

    /**
     * @param int      $httpCode
     * @param string[] $headers
     * @param string   $body
     */
    public function __construct($httpCode, array $headers, $body)
    {
        $this->code = (int) $httpCode;

        $this->headers = array();
        foreach ($headers as $key => $header) {
            $this->headers[(string) $key] = (string) $header;
        }

        $this->body = (string) $body;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
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
}
