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

namespace Shopgate\ConnectSdk;

use Shopgate\ConnectSdk\Service\BulkImport;
use Shopgate\ConnectSdk\Service\Catalog;

class ShopgateSdk
{
    const REQUEST_TYPE_DIRECT = 'direct';
    const REQUEST_TYPE_EVENT  = 'event';

    /** @var ClientInterface */
    private $client;

    /** @var Catalog */
    private $catalog;

    /** @var BulkImport */
    private $bulkImport;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->client = isset($config['client'])
            ? $config['client']
            : Client::createInstance($config['merchantCode'], $config['apiKey']);

        $this->setServices(isset($config['services']) ? $config['services'] : []);
    }

    /**
     * @return Catalog
     */
    public function getCatalogService()
    {
        return $this->catalog;
    }

    /**
     * @return BulkImport
     */
    public function getBulkImportService()
    {
        return $this->bulkImport;
    }

    /**
     * @param object[] $serviceArgs [string, object]
     */
    private function setServices($serviceArgs)
    {
        foreach (['catalog', 'bulkImport'] as $service) {
            $this->$service = isset($serviceArgs[$service])
                ? $serviceArgs[$service]
                : $this->instantiateClass($service);
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function instantiateClass($name)
    {
        $class = 'Shopgate\ConnectSdk\Service\\' . ucfirst($name);

        return new $class($this->client);
    }
}
