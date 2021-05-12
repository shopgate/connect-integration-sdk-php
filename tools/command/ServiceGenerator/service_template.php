<?php

/**
 * @var string $serviceName
 * @var string $templateDir
 * @var array $swagger
 * @var Symfony\Component\Console\Output\OutputInterface $output
 */
?>

/**
 * Copyright Shopgate GmbH.
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
 * @copyright Shopgate GmbH
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Service;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Helper\Json;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\ShopgateSdk;

class <?= $serviceName ?>
{
    const SERVICE_NAME = '<?= strtolower($serviceName) ?>';

    /** @var ClientInterface */
    private $client;

    /** @var Json */
    private $jsonHelper;

    /**
     * @param ClientInterface $client
     * @param Json $jsonHelper
     */
    public function __construct(ClientInterface $client, Json $jsonHelper)
    {
        $this->client = $client;
        $this->jsonHelper = $jsonHelper;
    }
<?php
 foreach ($swagger['paths'] as $path => $paths) {
     $subPath = str_replace('/merchants/{merchantCode}/', '', $path);

     preg_match_all('/{(\w+)}/', $subPath, $matches);

     $pathSubstitutions = $matches[1];

     $methodArgs = array_map(function ($p) {
         return '$' . $this->camelCase($p);
     }, $pathSubstitutions);

     $methodArgsStr = '';
     if (!empty($methodArgs)) {
         $methodArgsStr = implode(', ', $methodArgs) . ', ';

         foreach ($matches[0] as $k => $match) {
             $subPath = str_replace($match, "' . $" . $matches[1][$k] . " . '", $subPath);
         }
     }

     if (!empty($paths['get'])) {
         $pathParams = $paths['get'];
         include $templateDir . '/method_get.php';
     }

     if (!empty($paths['post'])) {
         $pathParams = $paths['post'];
         include $templateDir . '/method_post.php';
     }

     if (!empty($paths['delete'])) {
         $pathParams = $paths['delete'];
         include $templateDir . '/method_delete.php';
     }

     if (empty($paths['get']) && empty($paths['post']) && empty($paths['delete'])) {
         $output->writeln('Path is unmapped, please check: ' . $path);
     }
 }
?>
}

