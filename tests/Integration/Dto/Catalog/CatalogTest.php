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

/** @noinspection PhpUnhandledExceptionInspection */

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Catalog;

use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Tests\Integration\AbstractCatalogTest;

class CatalogTest extends AbstractCatalogTest
{
    public function testDeleteCatalog()
    {
        $this->markTestSkipped('will continue working on this test when the other methods are available');

        // Arrange
        $catalog = $this->provideSampleCatalog();
        $this->sdk->getCatalogService()->addCatalogs([$catalog]);

        // Act
        $this->sdk->getCatalogService()->deleteCatalog($catalog->getCode());

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_CATALOG,
            [$catalog->getCode()]
        );

        // Assert
        try {
            $this->sdk->getCatalogService()->getCatalog($catalog->getCode());
        } catch (NotFoundException $exception) {

            return;
        }

        $this->fail('NotFoundException was not thrown!');
    }
}
