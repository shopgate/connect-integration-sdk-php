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
use Shopgate\ConnectSdk\Dto\Catalog\Catalog\Get;
use Shopgate\ConnectSdk\Dto\Catalog\Catalog\GetList;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Exception\Exception;

class GetListTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateCatalog()
    {
        $entry = [
            'meta' => [
                'limit' => 2
            ],
            'catalogs' => [
                [
                    'code' => 'aCode',
                    'name' => 'aName',
                    'defaultLocaleCode' => 'localeCode',
                    'defaultCurrencyCode' => 'currencyCode',
                    'isDefault' => true,
                    'parentCatalogCode' => 'parentCatalogCode'
                ],
                [
                    'code' => 'aCode2',
                    'name' => 'aName2',
                    'defaultLocaleCode' => 'localeCode2',
                    'defaultCurrencyCode' => 'currencyCode2',
                    'isDefault' => false,
                    'parentCatalogCode' => 'parentCatalogCode2'
                ]
            ]
        ];
        $getList = new GetList($entry);
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(2, $getList->getMeta()->getLimit());

        $catalogs = $getList->getCatalogs();
        $this->assertCount(2, $catalogs);
        $this->assertInstanceOf(Get::class, $catalogs[0]);
        $this->assertInstanceOf(Get::class, $catalogs[1]);
        $this->assertEquals('aCode', $catalogs[0]->getCode());
        $this->assertEquals('aCode2', $catalogs[1]->getCode());
    }
}
