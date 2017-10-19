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

class GetV2 implements RequestHandlerInterface
{
    /** @var Authenticator\AuthenticatorInterface */
    private $authenticator;

    /** @var Repository\AbstractPathInfo */
    private $repository;

    /**
     * GetV2 constructor.
     *
     * @param Repository\AbstractClientCredentials $clientCredentialsRepository
     * @param Repository\AbstractPathInfo          $pathInfoRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Repository\AbstractClientCredentials $clientCredentialsRepository,
        Repository\AbstractPathInfo $pathInfoRepository
    ) {
        if (empty($clientCredentialsRepository)) {
            throw new \InvalidArgumentException("Argument '\$clientCredentialsRepository' is invalid!");
        }

        if (empty($pathInfoRepository)) {
            throw new \InvalidArgumentException("Argument '\$pathInfoRepository' is invalid!");
        }

        $this->authenticator = new Authenticator\BasicAuth($clientCredentialsRepository);
        $this->repository = $pathInfoRepository;
    }

    public function getAuthenticator()
    {
        return $this->authenticator;
    }

    /**
     * @param Request\Request $request
     *
     * @return Response
     *
     * @throws \RuntimeException
     */
    public function handle(Request\Request $request)
    {
        // check if there is a file available to be shown
        $specificationPath = $this->repository->getSpecificationPath();
        if (!file_exists($specificationPath)) {
            throw new \RuntimeException("Specification file '{$specificationPath}' does not exist or can't be read.");
        }

        // load file contents to be returned as a response for display
        $specification = file_get_contents($specificationPath);
        $responseHeaders = [
            "Content-Type"     => "text/json; charset=utf-8",
            "Cache-Control"    => "max-age=3600",
            "Content-Language" => "en",
            "Content-Length"   => (string) strlen($specification)
        ];
        return new Response(200, $responseHeaders, $specification);
    }
}
