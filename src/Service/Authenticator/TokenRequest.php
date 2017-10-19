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
     */
    public function authenticate(Request $request)
    {
        // check basic authorization, before trying to validate any tokens
        $this->basicAuthAuthenticator->authenticate($request);

        // TODO: check grant type and authenticate with user credentials or with a refresh_token (client_credentials has been authenticated already)
    }
}
