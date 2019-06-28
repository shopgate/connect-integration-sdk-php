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

use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Exception\Exception;

abstract class CatalogTest extends ShopgateSdkTest
{
    const CATALOG_SERVICE         = 'catalog';
    const METHOD_DELETE_CATEGORY  = 'deleteCategory';
    const METHOD_DELETE_PRODUCT   = 'deleteProduct';
    const METHOD_DELETE_ATTRIBUTE = 'deleteAttribute';
    const METHOD_DELETE_REQUEST_META  = [
        self::METHOD_DELETE_CATEGORY  => ['force' => true],
        self::METHOD_DELETE_PRODUCT   => [],
        self::METHOD_DELETE_ATTRIBUTE => [],
    ];
    const PRODUCT_CODE                = 'integration-test';
    const PRODUCT_CODE_SECOND         = 'integration-test-2';
    const PARENT_CATEGORY_CODE        = 'parent-integration-test';
    const CATEGORY_CODE               = 'integration-test';
    const CATEGORY_CODE_SECOND        = 'integration-test-2';
    const SAMPLE_ATTRIBUTE_CODE       = 'attribute_code_1';
    const SAMPLE_ATTRIBUTE_VALUE_CODE = 'attribute_value_code_1';
    const SAMPLE_EXTRA_CODE           = 'extra_code_1';
    const SAMPLE_EXTRA_CODE_2         = 'extra_code_2';
    const SAMPLE_EXTRA_VALUE_CODE     = 'extra_value_code_1';
    const SAMPLE_EXTRA_VALUE_CODE_2   = 'extra_value_code_2';

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_CATEGORY,
                self::METHOD_DELETE_PRODUCT,
                self::METHOD_DELETE_ATTRIBUTE,
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
            $this->provideSampleCreateCategory(self::CATEGORY_CODE_SECOND, 'Integration Test Category 2', 2),
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
     * @throws Exception
     */
    protected function createSampleAttribute()
    {
        $attribute = new Attribute\Create;
        $attribute->setCode(self::SAMPLE_ATTRIBUTE_CODE)
            ->setType(Attribute\Create::TYPE_TEXT)
            ->setUse(Attribute\Create::USE_OPTION)
            ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');

        $attributeName = new Name();
        $attributeName->add('de-de', 'Attribute de');
        $attributeName->add('en-us', 'Attribute en');
        $attribute->setName($attributeName);

        $attribute->setValues([$this->provideSampleAttributeValue()]);

        $this->sdk->getCatalogService()->addAttributes([$attribute], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            [self::SAMPLE_ATTRIBUTE_CODE]
        );
    }

    /**
     * @throws Exception
     */
    protected function createSampleExtras()
    {
        $this->sdk->getCatalogService()->addAttributes($this->provideSampleExtras(), ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CATALOG_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            [self::SAMPLE_EXTRA_CODE, self::SAMPLE_EXTRA_CODE_2]
        );
    }

    /**
     * @return AttributeValue\Create
     */
    protected function provideSampleAttributeValue()
    {
        $attributeValue = new AttributeValue\Create();
        $attributeValue->setCode(self::SAMPLE_ATTRIBUTE_VALUE_CODE);
        $attributeValue->setSequenceId(1);

        $attributeValueName = new AttributeValue\Dto\Name();
        $attributeValueName->add('de-de', 'Attribute Value 1 de');
        $attributeValueName->add('en-us', 'Attribute Value 1 en');
        $attributeValue->setName($attributeValueName);

        $attributeValueSwatch = new AttributeValue\Dto\Swatch();
        $attributeValueSwatch->setType(AttributeValue::SWATCH_TYPE_IMAGE);
        $attributeValueSwatch->setValue('https://www.google.de/image');
        $attributeValue->setSwatch($attributeValueSwatch);

        return $attributeValue;
    }

    /**
     * @return array
     */
    protected function provideSampleExtras()
    {
        $extra = new Attribute\Create;
        $extra->setCode(self::SAMPLE_EXTRA_CODE)
            ->setType(Attribute\Create::TYPE_TEXT)
            ->setUse(Attribute\Create::USE_EXTRA)
            ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');

        $extraName = new Name();
        $extraName->add('de-de', 'Extra 1 de');
        $extraName->add('en-us', 'Extra 1 en');
        $extra->setName($extraName);

        $extraValue = new AttributeValue\Create();
        $extraValue->setCode(self::SAMPLE_EXTRA_VALUE_CODE);
        $extraValue->setSequenceId(1);

        $extraValueName = new AttributeValue\Dto\Name();
        $extraValueName->add('de-de', 'Extra 1 Attribute de');
        $extraValueName->add('en-us', 'Extra 1 Attribute en');
        $extraValue->setName($extraValueName);

        $extra->setValues([$extraValue]);

        $extraSecond = new Attribute\Create;
        $extraSecond->setCode(self::SAMPLE_EXTRA_CODE_2)
            ->setType(Attribute\Create::TYPE_TEXT)
            ->setUse(Attribute\Create::USE_EXTRA)
            ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');

        $extraSecondName = new Name();
        $extraSecondName->add('de-de', 'Extra 2 de');
        $extraSecondName->add('en-us', 'Extra 2 en');
        $extraSecond->setName($extraSecondName);

        $extraSecondValue = new AttributeValue\Create();
        $extraSecondValue->setCode(self::SAMPLE_EXTRA_VALUE_CODE_2);
        $extraSecondValue->setSequenceId(1);

        $extraSecondValueName = new AttributeValue\Dto\Name();
        $extraSecondValueName->add('de-de', 'Extra 2 Attribute de');
        $extraSecondValueName->add('en-us', 'Extra 2 Attribute en');
        $extraSecondValue->setName($extraSecondValueName);

        $extraSecond->setValues([$extraSecondValue]);

        return [$extra, $extraSecond];
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
