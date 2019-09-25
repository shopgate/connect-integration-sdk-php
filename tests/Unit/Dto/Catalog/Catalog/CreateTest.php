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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Catalog;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Catalog\Create;
use Shopgate\ConnectSdk\Exception\Exception;

class CreateTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateCatalog()
    {
        $entry = [
            'code' => 'aCode',
            'name' => 'aName',
            'defaultLocaleCode' => 'localeCode',
            'defaultCurrencyCode' => 'currencyCode',
            'isDefault' => true,
            'parentCatalogCode' => 'parentCatalogCode'
        ];

        $create = new Create($entry);

        $this->assertEquals('aName', $create->getName());
        $this->assertEquals('aCode', $create->getCode());
        $this->assertEquals('localeCode', $create->getDefaultLocaleCode());
        $this->assertEquals('currencyCode', $create->getDefaultCurrencyCode());
        $this->assertEquals(true, $create->getIsDefault());
        $this->assertEquals('parentCatalogCode', $create->getParentCatalogCode());
    }
}
