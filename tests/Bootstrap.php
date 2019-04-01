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

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../src/Repository/RepositoryInterface.php';
require_once dirname(__FILE__) . '/../src/Repository/AbstractClientCredentials.php';
require_once dirname(__FILE__) . '/../src/Service/Authenticator/AuthenticatorInterface.php';
require_once dirname(__FILE__) . '/../src/Service/Authenticator/BasicAuth.php';
require_once dirname(__FILE__) . '/../src/Service/Authenticator/Exception/Forbidden.php';
require_once dirname(__FILE__) . '/../src/Service/Authenticator/Exception/InvalidAuthenticator.php';
require_once dirname(__FILE__) . '/../src/Service/Authenticator/Exception/Unauthorized.php';
require_once dirname(__FILE__) . '/../src/Service/RequestHandler/Exception/InvalidRequestHandler.php';
require_once dirname(__FILE__) . '/../src/ValueObject/Request/Request.php';
require_once dirname(__FILE__) . '/../src/ValueObject/Response.php';
require_once dirname(__FILE__) . '/Stubs/Repository/ClientCredentials.php';
