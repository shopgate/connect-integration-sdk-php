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

namespace Shopgate\CloudIntegrationSdk\Service\Authenticator;

use Shopgate\CloudIntegrationSdk\Repository;
use Shopgate\CloudIntegrationSdk\ValueObject\Request;
use Shopgate\CloudIntegrationSdk\ValueObject\Username;
use Shopgate\CloudIntegrationSdk\ValueObject\Password;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenId;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenType;

class TokenRequest implements AuthenticatorInterface
{
    /** @var Repository\AbstractToken */
    private $tokenRepository;

    /** @var Repository\AbstractUser */
    private $userRepository;

    /** @var BasicAuth */
    private $basicAuthAuthenticator;

    /**
     * TokenRequest constructor.
     *
     * @param Repository\AbstractClientCredentials $clientCredentialsRepository
     * @param Repository\AbstractToken             $tokenRepository
     * @param Repository\AbstractUser              $userRepository
     */
    public function __construct(
        Repository\AbstractClientCredentials $clientCredentialsRepository,
        Repository\AbstractToken $tokenRepository,
        Repository\AbstractUser $userRepository
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->basicAuthAuthenticator = new BasicAuth($clientCredentialsRepository);
    }

    /**
     * @param Request $request
     *
     * @throws Exception\Unauthorized
     * @throws Exception\BadRequest
     */
    public function authenticate(Request $request) {
        // check basic authorization, before trying to validate any tokens
        $this->basicAuthAuthenticator->authenticate($request);

        // check authentication based on grant type
        $usernameKey = 'username';
        $passwordKey = 'password';
        $refreshTokenKey = 'refresh_token';
        switch ($this->parseParam($request, 'grant_type')) {
            case 'client_credentials':
                // no additional authentication required
                break;
            case $passwordKey:
                $username = $this->parseParam($request, $usernameKey);
                $password = $this->parseParam($request, $passwordKey);

                // check if given credentials are valid or not
                if (empty($username) || empty($password) || is_null($this->userRepository->getUserIdByCredentials(
                    new Username($username),
                    new Password($password)))
                ) {
                    throw new Exception\Unauthorized('Invalid user credentials provided.');
                }

                break;
            case $refreshTokenKey:
                // get refresh token from params and try to load the refresh token
                $refreshToken = $this->parseParam($request, $refreshTokenKey);
                $token = $this->tokenRepository->loadToken(
                    new TokenId($refreshToken),
                    new TokenType\RefreshToken()
                );

                // check if a refresh token was found
                if (empty($refreshTokenParam) || is_null($token)) {
                    throw new Exception\Unauthorized('Invalid refresh_token provided.');
                }

                // check if the refresh_token is still valid and is not expired
                if ($token->getExpires() !== null && strtotime($token->getExpires() < time())) {
                    throw new Exception\Unauthorized('The refresh_token provided is expired.');
                }

                break;
            default:
                throw new Exception\BadRequest('Unsupported or no grant_type provided.');
        }
    }


    /**
     * @param Request $request
     * @param string  $paramName
     *
     * @return string | null
     *
     * @throws Exception\BadRequest
     */
    private function parseParam(Request $request, $paramName)
    {
        $data = [];
        parse_str(parse_url($request->getUri(), PHP_URL_QUERY), $data);

        // parse either from request query or from request body
        if (!empty($data[$paramName])) {
            return $data[$paramName];
        } else {
            $contentTypeKey = 'Content-Type';
            $requestHeaders = $request->getHeaders();

            // check if there is a content type header provided
            if (empty($requestHeaders[$contentTypeKey])) {
                throw new Exception\BadRequest('Invalid request body.');
            }

            // check if the provided content type is supported
            switch($this->parseContentType($requestHeaders[$contentTypeKey])) {
                case 'application/json':
                    $data = json_decode($request->getBody(), true);
                    if (!empty($data[$paramName])) {
                        return $data[$paramName];
                    }
                    break;
                case 'application/x-www-form-urlencoded':
                    parse_str($request->getBody(), $data);
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

    private function parseContentType($contentType) {

    }
}
