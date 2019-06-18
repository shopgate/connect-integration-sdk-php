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

namespace Shopgate\ConnectSdk\Tests\Integration;

use Shopgate\ConnectSdk\Dto\Catalog\Category;

abstract class CatalogTest extends ShopgateSdkTest
{
    const CATALOG_SERVICE        = 'catalog';
    const METHOD_DELETE_CATEGORY = 'deleteCategory';
    const METHOD_DELETE_PRODUCT  = 'deleteProduct';

    const PRODUCT_CODE         = 'integration-test';
    const PARENT_CATEGORY_CODE = 'parent-integration-test';
    const CATEGORY_CODE        = 'integration-test';
    const CATEGORY_CODE_SECOND = 'integration-test-2';

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_CATEGORY,
                self::METHOD_DELETE_PRODUCT
            ]
        );
    }

    /**
     * @return Category\Create[]
     */
    protected function provideSampleCategories()
    {
        return [
            $this->provideSampleCreateCategory(self::CATEGORY_CODE, 'Integration Test Category 1', 1),
            $this->provideSampleCreateCategory(self::CATEGORY_CODE_SECOND, 'Integration Test Category 2', 2)
        ];
    }

    /**
     * @param string $code
     * @param string $name
     * @param int    $sequenceId
     * @param string $image
     * @param string $url
     * @param string $description
     * @param string $parentCategoryCode
     *
     * @return Category\Create
     */
    protected function provideSampleCreateCategory(
        $code,
        $name,
        $sequenceId,
        $image = null,
        $url = null,
        $description = null,
        $parentCategoryCode = null

    ) {
        $category = new Category\Create();
        $category->setCode($code)
                 ->setName(new Category\Dto\Name(['en-us' => $name]))
                 ->setSequenceId($sequenceId);
        if ($url) {
            $category->setUrl($url);
        }
        if ($description) {
            $translatedDescription = new Category\Dto\Description(['en-us' => $description]);
            $category->setDescription($translatedDescription);
        }
        if ($image) {
            $category->setImage($image);
        }
        if ($parentCategoryCode) {
            $category->setParentCategoryCode($parentCategoryCode);
        }

        return $category;
    }

    /**
     * @param Category\Create[] $categories
     *
     * @return string[]
     */
    protected function getCategoryCodes($categories)
    {
        $categoryCodes = [];
        foreach ($categories as $category) {
            $categoryCodes[] = $category->code;
        }

        return $categoryCodes;
    }
}
