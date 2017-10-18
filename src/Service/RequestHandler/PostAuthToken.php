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

use Shopgate\CloudIntegrationSdk\Service\Authenticator;
use Shopgate\CloudIntegrationSdk\Repository;

use Shopgate\CloudIntegrationSdk\ValueObject\Request;
use Shopgate\CloudIntegrationSdk\ValueObject\Response;

class PostAuthToken implements RequestHandlerInterface
{
    /** @var Authenticator\AuthenticatorInterface */
    private $authenticator;

    /**
     * PostAuthToken constructor.
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
        $this->authenticator = new Authenticator\Token(
            $clientCredentialsRepository, $tokenRepository, $userRepository
        );
    }

    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    public function handle(Request $request)
    {
        return new Response(); // TODO: Implement handle() method.
    }
}
