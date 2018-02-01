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
use Shopgate\CloudIntegrationSdk\ValueObject\TokenId;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenType;
use Shopgate\CloudIntegrationSdk\ValueObject\UserId;

class ResourceAccess implements AuthenticatorInterface
{
    /** @var Repository\AbstractToken */
    private $repository;

    /** @var UserId  */
    private $resourceOwnerId;

    /**
     * @param Repository\AbstractToken $accessTokenRepository
     * @param UserId |null             $resourceOwnerId
     */
    public function __construct(Repository\AbstractToken $accessTokenRepository, UserId $resourceOwnerId = null)
    {
        $this->repository      = $accessTokenRepository;
        $this->resourceOwnerId = $resourceOwnerId;
    }

    /**
     * @inheritdoc
     *
     * @throws Exception\Unauthorized
     * @throws Request\Exception\BadRequest
     * @throws \RuntimeException
     */
    public function authenticate(Request\Request $request)
    {
        // read access_token parameter from header
        $authHeader = $request->getHeader('Authorization');
        if (empty($authHeader)) {
            throw new Request\Exception\BadRequest('Authorization header missing.');
        }

        // check if a token is provided and marked as "bearer"
        $authorization = explode(' ', $authHeader);
        if (empty($authorization[0]) || strtolower($authorization[0]) !== 'bearer') {
            throw new Exception\Unauthorized(
                "Wrong authorization data provided: '$authorization'. Required: 'Bearer'."
            );
        }
        if (empty($authorization[1])) {
            throw new Exception\Unauthorized(
                'Missing Bearer token.'
            );
        }

        // check if the given access_token is valid and not expired
        try {
            $accessToken = $this->repository->loadToken(new TokenId($authorization[1]), new TokenType\AccessToken());
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to load access token from repository.', 0, $e);
        }
        if (null === $accessToken) {
            throw new Exception\Unauthorized('The bearer token provided does not exist.');
        }
        if (
            $accessToken->getExpires() !== null &&
            (string) $accessToken->getExpires() !== '' &&
            strtotime($accessToken->getExpires()) < time()
        ) {
            throw new Exception\Unauthorized('The bearer token provided is expired.');
        }

        // check if an owner was provided for the requested uri and if it's the same as the access_token holder
        if ($this->resourceOwnerId !== null && (string) $this->resourceOwnerId !== (string) $accessToken->getUserId()) {
            throw new Exception\Unauthorized('Requested resource is owned by another user.');
        }
    }
}
