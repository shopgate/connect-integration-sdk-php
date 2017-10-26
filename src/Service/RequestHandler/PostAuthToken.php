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

namespace Shopgate\CloudIntegrationSdk\Service\RequestHandler;

use Shopgate\CloudIntegrationSdk\Repository;
use Shopgate\CloudIntegrationSdk\Service\Authenticator;

use Shopgate\CloudIntegrationSdk\ValueObject\Base;
use Shopgate\CloudIntegrationSdk\ValueObject\Request;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenType\AbstractTokenType;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenType\AccessToken;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenType\RefreshToken;
use Shopgate\CloudIntegrationSdk\ValueObject\ClientId;
use Shopgate\CloudIntegrationSdk\ValueObject\Password;
use Shopgate\CloudIntegrationSdk\ValueObject\Response;
use Shopgate\CloudIntegrationSdk\ValueObject\Token;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenId;
use Shopgate\CloudIntegrationSdk\ValueObject\UserId;
use Shopgate\CloudIntegrationSdk\ValueObject\Username;

class PostAuthToken implements RequestHandlerInterface
{
    /** @var Authenticator\AuthenticatorInterface */
    private $authenticator;

    /** @var Repository\AbstractClientCredentials */
    private $clientCredentialsRepository;

    /** @var Repository\AbstractToken */
    private $tokenRepository;

    /** @var Repository\AbstractUser */
    private $userRepository;

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
        $this->authenticator = new Authenticator\TokenRequest(
            $clientCredentialsRepository, $tokenRepository, $userRepository
        );

        $this->clientCredentialsRepository = $clientCredentialsRepository;
        $this->tokenRepository             = $tokenRepository;
        $this->userRepository              = $userRepository;
    }

    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    /**
     * @param Request\Request $request
     * @param null            $uriParams
     *
     * @return Response
     *
     * @throws Authenticator\Exception\Unauthorized
     * @throws Request\Exception\BadRequest
     * @throws \RuntimeException
     */
    public function handle(Request\Request $request, $uriParams = null)
    {
        $responseBody = json_encode($this->generateTokens($request));
        $responseHeaders = array(
            'Content-Type'     => 'text/json; charset=utf-8',
            'Cache-Control'    => 'no-cache',
            'Content-Language' => 'en',
            'Content-Length'   => (string) strlen($responseBody)
        );
        return new Response(200, $responseHeaders, $responseBody);
    }

    /**
     * @param Request\Request $request
     *
     * @return \string[]
     *
     * @throws Authenticator\Exception\Unauthorized
     * @throws Request\Exception\BadRequest
     * @throws \RuntimeException
     */
    private function generateTokens(Request\Request $request) {
        $usernameKey = 'username';
        $passwordKey = 'password';
        $refreshTokenKey = 'refresh_token';
        switch ($request->getParam('grant_type')) {
            case 'client_credentials':
                // no userId available
                $userId          = null;

                // no previous tokens to invalidate
                $oldAccessToken  = null;
                $oldRefreshToken = null;

                break;
            case $passwordKey:
                // check if credentials available
                $username = new Username($request->getParam($usernameKey));
                $password = new Password($request->getParam($passwordKey));
                if ((string) $username === '' || (string) $password === '') {
                    throw new Request\Exception\BadRequest('No username or password specified.');
                }

                // check credentials
                $userId = $this->userRepository->getUserIdByCredentials($username, $password);
                if (null === $userId) {
                    throw new Authenticator\Exception\Unauthorized('The given user credentials are invalid.');
                }

                try {
                    $oldAccessToken  = $this->tokenRepository->loadTokenByUserId($userId, new AccessToken());
                    $oldRefreshToken = $this->tokenRepository->loadTokenByUserId($userId, new RefreshToken());
                } catch (\Exception $e) {
                    throw new \RuntimeException('Failed to load access and/or refresh token from repository.', 0, $e);
                }

                break;
            case $refreshTokenKey:
                // find userId by refresh token
                $refreshTokenId = new TokenId($request->getParam($refreshTokenKey));
                try {
                    $oldRefreshToken = $this->tokenRepository->loadToken($refreshTokenId, new RefreshToken());
                } catch (\Exception $e) {
                    throw new \RuntimeException('Failed to load refresh token from repository.', 0, $e);
                }
                $userId = $oldRefreshToken->getUserId();

                // check if an old access token exists and load it
                if (null !== $userId) {
                    try {
                        $oldAccessToken = $this->tokenRepository->loadTokenByUserId($userId, new AccessToken());
                    } catch (\Exception $e) {
                        throw new \RuntimeException('Failed to load access token from repository.', 0, $e);
                    }
                } else {
                    $oldAccessToken = null;
                }

                break;
            default:
                throw new Request\Exception\BadRequest('Unsupported or no grant_type provided.');
        }

        $expiresIn = 60;
        $accessToken  = $this->createToken(new AccessToken(), $userId, $expiresIn);
        $refreshToken = $this->createToken(new RefreshToken(), $userId, $expiresIn);

        // clean up with the old tokens
        $this->expireToken($oldAccessToken);
        $this->expireToken($oldRefreshToken);

        return $this->createResponse($accessToken, $refreshToken, $expiresIn);
    }

    /**
     * @param AbstractTokenType $type
     * @param UserId            $userId
     * @param int               $expirationTime
     *
     * @return Token
     *
     * @throws \RuntimeException
     */
    private function createToken($type, $userId, $expirationTime = 60) {
        $expirationDateString = new Base\String(date('Y-m-dTH:i:s'), time() + 60 * $expirationTime);
        try {
            $result = new Token(
                $type,
                $this->tokenRepository->generateTokenId($type),
                new ClientId($this->clientCredentialsRepository->getClientId()),
                $userId,
                $expirationDateString,
                null // no scopes supported, yet
            );

            $this->tokenRepository->saveToken($result);
        } catch (\Exception $e) {
            throw new \RuntimeException("Token of type '{$type->getValue()}' failed to to create or save.", 0, $e);
        }

        return $result;
    }

    /**
     * @param Token $token
     *
     * @throws \RuntimeException
     */
    private function expireToken(Token $token) {
        $currentDateString = new Base\String(date('Y-m-dTH:i:s'));
        if (null !== $token) {
            try {
                $this->tokenRepository->saveToken(
                    new Token(
                        $token->getType(),
                        $token->getTokenId(),
                        $token->getClientId(),
                        $token->getUserId(),
                        $currentDateString,
                        $token->getScope()
                    )
                );
            } catch (\Exception $e) {
                throw new \RuntimeException("Failed to save token of type: {$token->getType()->getValue()}", 0, $e);
            }
        }
    }

    /**
     * @param Token $accessToken
     * @param Token $refreshToken
     * @param int   $expiresIn
     *
     * @return string[]
     */
    private function createResponse(Token $accessToken, Token $refreshToken, $expiresIn) {
        return array(
            'token_type' => 'Bearer',
            (string) $accessToken->getType() => (string) $accessToken->getTokenId(),
            'expires_in' => $expiresIn,
            (string) $refreshToken->getType() => (string) $refreshToken->getTokenId(),
            'scope' => (string) $accessToken->getScope(),
            'user_id' => (string) $accessToken->getUserId(),
        );
    }
}
