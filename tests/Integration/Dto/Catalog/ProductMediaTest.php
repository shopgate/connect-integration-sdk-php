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

use Dto\Exceptions\InvalidDataTypeException;
use Shopgate\ConnectSdk\Dto\Catalog\ProductMedia;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;

class ProductMediaTest extends ProductTest
{
    /**
     * @throws InvalidDataTypeException
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    public function testMediaCreation()
    {
        $this->markTestSkipped('This endpoint does not exist yet: POST product/{code}/media');
        // Arrange
        $prepared  = $this->prepareProductMinimum();
        $media     = $this->provideMedia();
        $mediaList = new ProductMedia\Create([$media]);

        // Act
        $this->sdk->getCatalogService()->addProducts([$prepared], ['requestType' => 'direct']);
        $this->sdk->getCatalogService()->addProductMedia(self::PRODUCT_CODE, $mediaList);
        //sleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp after test run
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$prepared->code]);

        // Assert
        $returnedProduct = $this->sdk->getCatalogService()->getProduct($prepared->code);
        $this->assertEquals($prepared->code, $returnedProduct->getCode());
        $returnedMedia = $returnedProduct->getMedia();
        $this->assertCount(2, $returnedMedia);
        /** @var ProductMedia\Dto\Media $preparedMedia1 */
        $preparedMedia1 = $mediaList->get('en-us')->toArray()[0];
        $this->assertEquals($preparedMedia1->getCode(), $returnedMedia[0]->getCode());
    }
}
