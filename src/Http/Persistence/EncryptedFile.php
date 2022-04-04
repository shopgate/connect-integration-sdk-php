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

namespace Shopgate\ConnectSdk\Http\Persistence;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use kamermans\OAuth2\Persistence\TokenPersistenceInterface;
use kamermans\OAuth2\Token\Serializable;
use kamermans\OAuth2\Token\TokenInterface;
use kamermans\OAuth2\Token\TokenSerializer;
use Shopgate\ConnectSdk\Helper\Json;

class EncryptedFile implements TokenPersistenceInterface
{
    /** @var string */
    private $filepath;

    /** @var string */
    private $secretKey;

    /**
     * @param string $filepath
     * @param string $secretKey
     */
    public function __construct($filepath, $secretKey)
    {
        $this->filepath  = $filepath;
        $this->secretKey = $secretKey;
    }

    /**
     * @inheritDoc
     *
     * @throws EnvironmentIsBrokenException
     */
    public function saveToken(TokenInterface $token)
    {
        if (method_exists($token, 'serialize')) {
            $token = $token->serialize();
        }
        $encode = Json::encode($token);
        file_put_contents($this->filepath, Crypto::encryptWithPassword($encode, $this->secretKey), LOCK_EX);
    }

    /**
     * @inheritDoc
     *
     * @throws EnvironmentIsBrokenException
     * @throws WrongKeyOrModifiedCiphertextException
     */
    public function restoreToken(TokenInterface $token)
    {
        if (!file_exists($this->filepath)) {
            return null;
        }

        $fileContents = @file_get_contents($this->filepath);
        $decrypt      = Crypto::decryptWithPassword($fileContents, $this->secretKey);
        $data = @json_decode($decrypt, true);

        if (!is_array($data)) {
            return null;
        }

        if (method_exists($token, 'unserialize')) {
            $token = $token->unserialize($data);
        }

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function deleteToken()
    {
        if (file_exists($this->filepath)) {
            @unlink($this->filepath);
        }
    }

    /**
     * @inheritDoc
     */
    public function hasToken()
    {
        return file_exists($this->filepath);
    }
}
