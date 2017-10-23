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
     * @param Request\Request $request
     *
     * @throws Exception\Unauthorized
     * @throws Request\Exception\BadRequest
     */
    public function authenticate(Request\Request $request) {
        // check basic authorization, before trying to validate any tokens
        $this->basicAuthAuthenticator->authenticate($request);

        // check authentication based on grant type
        $usernameKey = 'username';
        $passwordKey = 'password';
        $refreshTokenKey = 'refresh_token';
        switch ($request->getParam('grant_type')) {
            case 'client_credentials':
                // no additional authentication required
                break;
            case $passwordKey:
                $this->authenticateGrantTypePassword($request, $usernameKey, $passwordKey);
                break;
            case $refreshTokenKey:
                $this->authenticateGrantTypeRefreshToken($request, $refreshTokenKey);
                break;
            default:
                throw new Request\Exception\BadRequest('Unsupported or no grant_type provided.');
        }
    }

    /**
     * @param Request\Request $request
     * @param string  $usernameKey
     * @param string  $passwordKey
     *
     * @throws Exception\Unauthorized
     * @throws Request\Exception\BadRequest
     */
    private function authenticateGrantTypePassword(Request\Request $request, $usernameKey, $passwordKey) {
        $username = $request->getParam($usernameKey);
        $password = $request->getParam($passwordKey);

        // check if given credentials are valid or not
        if (empty($username) || empty($password) || is_null($this->userRepository->getUserIdByCredentials(
            new Username($username),
            new Password($password)))
        ) {
            throw new Exception\Unauthorized('Invalid user credentials provided.');
        }
    }

    /**
     * @param Request\Request $request
     * @param string  $refreshTokenKey
     *
     * @throws Exception\Unauthorized
     * @throws Request\Exception\BadRequest
     */
    private function authenticateGrantTypeRefreshToken(Request\Request $request, $refreshTokenKey) {
        // get refresh token from params and try to load the refresh token
        $refreshToken = $request->getParam($refreshTokenKey);
        $token = $this->tokenRepository->loadToken(
            new TokenId($refreshToken),
            new TokenType\RefreshToken()
        );

        // check if a refresh token was found
        if (null === $token) {
            throw new Exception\Unauthorized('Invalid refresh_token provided.');
        }

        // check if the refresh_token is still valid and is not expired
        if ($token->getExpires() !== null && strtotime($token->getExpires()) < time()) {
            throw new Exception\Unauthorized('The refresh_token provided is expired.');
        }
    }
}
