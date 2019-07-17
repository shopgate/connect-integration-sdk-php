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

use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\LongDescription;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\ShortDescription;
use Shopgate\ConnectSdk\Tests\Integration\CatalogTest;

class ProductDescriptionsTest extends CatalogTest
{
    /**
     * Testing description pulling via endpoint
     */
    public function testDescriptionRetriever()
    {
        $product          = $this->prepareProductMinimum();
        $shortDescription = (new ShortDescription())
            ->add('en-us', 'My Short Description')
            ->add('ru-ru', 'Короткое описание');
        $longDescription  = (new LongDescription())
            ->add('en-us', 'My Long Description')
            ->add('ru-ru', 'Длинное описание');
        $product->setShortDescription($shortDescription)
                ->setLongDescription($longDescription);

        // Act
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);

        // Assert
        $ruProduct = $this->sdk->getCatalogService()->getProductDescriptions($product->code, ['localeCode' => 'ru-ru']);
        $this->assertEquals('Длинное описание', $ruProduct->getLongDescription());
        $this->assertEquals('Короткое описание', $ruProduct->getShortDescription());

        $enProduct = $this->sdk->getCatalogService()->getProductDescriptions($product->code, ['localeCode' => 'en-us']);
        $this->assertEquals('My Long Description', $enProduct->getLongDescription());
        $this->assertEquals('My Short Description', $enProduct->getShortDescription());
    }
}
