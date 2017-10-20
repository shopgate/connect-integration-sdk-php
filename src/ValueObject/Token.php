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

class Token
{
    /** @var  TokenId */
    private $tokenId;

    /** @var  ClientId */
    private $clientId;

    /** @var  UserId | null */
    private $userId;

    /** @var  TokenType\AbstractTokenType */
    private $type;

    /** @var  Base\String | null */
    private $expires;

    /**
     * Token constructor.
     *
     * @param TokenId                     $tokenId
     * @param ClientId                    $clientId
     * @param TokenType\AbstractTokenType $type
     * @param UserId|null                 $userId
     * @param Base\String|null            $expires
     */
    public function __construct(
        TokenId $tokenId,
        ClientId $clientId,
        TokenType\AbstractTokenType $type,
        UserId $userId = null,
        Base\String $expires = null
    ) {
        if (is_null($tokenId) || is_null($clientId) || is_null($type)) {
            throw new \InvalidArgumentException('Token id, client id and token type must be valid.');
        }

        $this->tokenId;
    }

    /**
     * @return TokenId
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * @return ClientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return UserId | null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return TokenType\AbstractTokenType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns a datestring or null if the the token does not expire.
     *
     * @return string | null
     */
    public function getExpires()
    {
        return $this->expires;
    }
}
