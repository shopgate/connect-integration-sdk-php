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

namespace Shopgate\CloudIntegrationSdk\Repository;

use Shopgate\CloudIntegrationSdk\ValueObject\Token;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenId;
use Shopgate\CloudIntegrationSdk\ValueObject\TokenType\AbstractTokenType;
use Shopgate\CloudIntegrationSdk\ValueObject\UserId;

abstract class AbstractToken implements RepositoryInterface
{
    /**
     * Generates a TokenId of the given type that is unique for the system, where it's created in
     *
     * @param AbstractTokenType $type
     *
     * @return TokenId
     *
     * @throws \Exception
     */
    abstract public function generateTokenId(AbstractTokenType $type);

    /**
     * @param TokenId           $token
     * @param AbstractTokenType $type
     *
     * @return Token | null Returns null only if there was no Token found or it's expired
     *
     * @throws \Exception Throws a custom exception if trying to load the token fails for some reason
     */
    abstract public function loadToken(TokenId $token, AbstractTokenType $type);

    /**
     * @param UserId            $userId
     * @param AbstractTokenType $type
     *
     * @return Token | null Returns null only if there was no Token found for the given UserId
     *
     * @throws \Exception Throws a custom exception if trying to load the token fails for some reason
     */
    abstract public function loadTokenByUserId($userId, AbstractTokenType $type);

    /**
     * Creates a new token in the data source or overwrites it, if the TokenId already exists
     *
     * @param Token $tokenData
     *
     * @throws \Exception Throws a custom exception if the call failed
     */
    abstract public function saveToken(Token $tokenData);
}
