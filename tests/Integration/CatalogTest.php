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
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Categories;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Extras;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\LongDescription;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Media;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\MapPricing;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\VolumePricing;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\ShortDescription;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Update;
use Shopgate\ConnectSdk\Exception\Exception;

abstract class CatalogTest extends ShopgateSdkTest
{
    const CATALOG_SERVICE             = 'catalog';
    const METHOD_DELETE_CATEGORY      = 'deleteCategory';
    const METHOD_DELETE_PRODUCT       = 'deleteProduct';
    const METHOD_DELETE_ATTRIBUTE     = 'deleteAttribute';

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

    const SAMPLE_CATALOG = 'NA Wholesale';

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::CATALOG_SERVICE,
            $this->sdk->getCatalogService(),
            [
                self::METHOD_DELETE_CATEGORY  => ['force' => true],
                self::METHOD_DELETE_PRODUCT   => [],
                self::METHOD_DELETE_ATTRIBUTE => [],
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
     * @param string      $code
     * @param string      $name
     * @param int         $sequenceId
     * @param string|null $image
     * @param string|null $url
     * @param string|null $description
     * @param string|null $parentCategoryCode
     * @param string|null $externalUpdateDate
     * @param string|null $status
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
        $parentCategoryCode = null,
        $externalUpdateDate = null,
        $status = null
    ) {
        $category = new Category\Create();
        $category->setCode($code)
                 ->setName(new Category\Dto\Name(['en-us' => $name]))
                 ->setSequenceId($sequenceId);
        if ($description) {
            $translatedDescription = new Category\Dto\Description(['en-us' => $description]);
            $category->setDescription($translatedDescription);
        }
        $url ? $category->setUrl($url) : null;
        $image ? $category->setImage($image) : null;
        $parentCategoryCode ? $category->setParentCategoryCode($parentCategoryCode) : null;
        $externalUpdateDate ? $category->setExternalUpdateDate($externalUpdateDate) : null;
        $status ? $category->setStatus($status) : null;

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

    /**‚
     * Retrieves the default product with minimum details needed
     *
     * @return Product\Create
     */
    protected function prepareProductMinimum()
    {
        $productPayload = new Product\Create();

        return $productPayload
            ->setCode(self::PRODUCT_CODE)
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setIsInventoryManaged(true);
    }

    /**
     * @param Product $product
     *
     * @return Update
     *
     * @throws Exception
     */
    protected function prepareProductMaximum($product = null)
    {
        if ($product === null) {
            $product = new Product\Create();
        }

        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, ['requestType' => 'direct']);
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, $sampleCategoryCodes);

        $this->createSampleAttribute();
        $this->createSampleExtras();

        $categories = $this->provideCategoryMapping();
        $identifiers = $this->provideIdentifiers();
        $price = $this->providePricing();
        $properties = $this->provideProperties();
        $shippingInformation = $this->provideShippingInformation();
        $media = $this->provideMedia();
        $options = $this->provideOptions();
        $extras = $this->provideExtras();
        $name = new Product\Dto\Name();
        $name->add('en-us', 'Productname in english');
        $name->add('de-de', 'Produktname in deutsch');

        $longName = new Product\Dto\LongName();
        $longName->add('en-us', 'Long Productname in english');
        $longName->add('de-de', 'Long Produktname in deutsch');

        return $product
            ->setName($name)
            ->setLongName($longName)
            ->setShortDescription(new ShortDescription(['en-us' => 'short description', 'de-de' => 'Kurzbeschreibung']))
            ->setLongDescription(new LongDescription(['en-us' => 'long description', 'de-de' => 'Beschreibung']))
            ->setCategories($categories)
            ->setProperties($properties)
            ->setMedia($media)
            ->setOptions($options)
            ->setExtras($extras)
            ->setCode(self::PRODUCT_CODE_SECOND)// required
            ->setParentProductCode('dfsdf7')
            ->setCatalogCode('PNW Retail')// required
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)// required
            ->setIdentifiers($identifiers)
            ->setPrice($price)// required
            ->setFulfillmentMethods(['one method', 'another method'])
            ->setUnit('kg')
            ->setIsSerialized(false)
            ->setStatus(Product\Create::STATUS_ACTIVE)// required
            ->setStartDate('2018-12-01T00:00:00.000Z')
            ->setEndDate('2020-12-01T00:00:00.000Z')
            ->setFirstAvailableDate('2019-06-03T00:00:00.000Z')
            ->setEolDate('2030-01-01T00:00:00.000Z')
            ->setIsInventoryManaged(true)// required
            ->setInventoryTreatment(Product\Create::INVENTORY_TREATMENT_PRE_ORDER)
            ->setShippingInformation($shippingInformation)
            ->setRating(3.5)
            ->setUrl('http://wwww.test.com')
            ->setIsTaxed(true)
            ->setTaxClass('f8c5c2e9')
            ->setMinQty(1)
            ->setMaxQty(100)
            ->setExternalUpdateDate('2019-06-01T00:00:00.000Z');
    }

    /**
     * @return Categories[]
     */
    protected function provideCategoryMapping()
    {
        $categoryMapping = new Product\Dto\Categories();
        $categoryMapping->setCode(self::CATEGORY_CODE)
            ->setIsPrimary(true);

        $categoryMapping2 = new Product\Dto\Categories();
        $categoryMapping2->setCode(self::CATEGORY_CODE_SECOND)
            ->setIsPrimary(false);

        return [$categoryMapping, $categoryMapping2];
    }

    protected function provideIdentifiers()
    {
        return (new Product\Dto\Identifiers())->setMfgPartNum('someMfgPartNum')
            ->setUpc('Universal-Product-Code')
            ->setEan('European Article Number')
            ->setIsbn('978-3-16-148410-0')
            ->setSku('stock_keeping_unit');
    }

    /**
     * @return VolumePricing[]
     */
    protected function provideVolumePricing()
    {
        $volumePricing1 = new Product\Dto\Price\VolumePricing();
        $volumePricing1->setMinQty(5)
            ->setMaxQty(20)
            ->setPrice(84.99)
            ->setSalePrice(83.99)
            ->setUnit('kg')
            ->setPriceType(Product\Dto\Price\VolumePricing::PRICE_TYPE_FIXED);

        $volumePricing2 = new Product\Dto\Price\VolumePricing();
        $volumePricing2->setMinQty(21)
            ->setMaxQty(100)
            ->setPrice(84.99)
            ->setSalePrice(-2)
            ->setUnit('kg')
            ->setPriceType(Product\Dto\Price\VolumePricing::PRICE_TYPE_RELATIVE);

        return [$volumePricing1, $volumePricing2];
    }

    /**
     * @return MapPricing[]
     */
    protected function provideMapPricing()
    {
        $mapPricing1 = new Product\Dto\Price\MapPricing();
        $mapPricing1->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(84.49);

        $mapPricing2 = new Product\Dto\Price\MapPricing();
        $mapPricing2->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(84.49);

        return [$mapPricing1, $mapPricing2];
    }

    /**
     * @return Price
     */
    protected function providePricing()
    {
        $volumePricing = $this->provideVolumePricing();
        $mapPricing = $this->provideMapPricing();

        return (new Product\Dto\Price())->setCurrencyCode(Product\Dto\Price::CURRENCY_CODE_USD)
            ->setCost(50)
            ->setPrice(90)
            ->setSalePrice(84.99)
            ->setVolumePricing($volumePricing)
            ->setUnit('kg')
            ->setMsrp(100)
            ->setMinPrice(80)
            ->setMaxPrice(90)
            ->setMapPricing($mapPricing);
    }

    /**
     * @return Properties\SubDisplayGroup
     */
    protected function provideSubDisplayGroup()
    {
        return (new Properties\SubDisplayGroup())
            ->add('de-de', 'deutsch')
            ->add('en-en', 'english');
    }

    /**
     * @return Product\Dto\Properties[]
     */
    protected function provideProperties()
    {
        $subDisplayGroup = $this->provideSubDisplayGroup();

        $property1 = new Product\Dto\Properties\Product();
        $property1->setCode('property_code_1')
            ->setName(new Properties\Name(['en-us' => 'property 1 english', 'de-de' => 'property 1 deutsch']))
            ->setValue(new Properties\Value(['stuff' => 'stuff value', 'other stuff' => 'other stuff value']))
            ->setDisplayGroup(Properties::DISPLAY_GROUP_FEATURES)
            ->setSubDisplayGroup($subDisplayGroup);

        $property2 = (new Product\Dto\Properties\Simple())->setCode('property_code_2')
            ->setName(
                new Properties\Name(
                    [
                        'en-us' => 'property 2 english',
                        'de-de' => 'property 2 deutsch'
                    ]
                )
            )
            ->setValue(new Properties\Value())
            ->setDisplayGroup('features')
            ->setSubDisplayGroup($subDisplayGroup);

        return [$property1, $property2];
    }

    /**
     * @return Product\Dto\ShippingInformation
     */
    protected function provideShippingInformation()
    {
        return (new Product\Dto\ShippingInformation())
            ->setIsShippedAlone(false)
            ->setHeight(0.5)
            ->setHeightUnit('m')
            ->setWidth(10)
            ->setWidthUnit('cm')
            ->setLength(5)
            ->setLengthUnit('dm')
            ->setWeight(5)
            ->setWeightUnit('kg');
    }

    /**
     * @return Media
     */
    protected function provideMedia()
    {
        $media1 = new Product\Dto\Media\Media();
        $media1->setCode('media_code_1')
            ->setType(Product\Dto\Media\Media::TYPE_IMAGE)
            ->setUrl('example.com/media1.jpg')
            ->setAltText('alt text 1')
            ->setSubTitle('Title Media 1')
            ->setSequenceId(0);

        $media2 = new Product\Dto\Media\Media();
        $media2->setCode('media_code_2')
            ->setType(Product\Dto\Media\Media::TYPE_VIDEO)
            ->setUrl('example.com/media2.mov')
            ->setAltText('alt text 2')
            ->setSubTitle('Title Media 2')
            ->setSequenceId(5);

        $media = new Media();
        $media->add('en-us', [$media1, $media2]);

        return $media;
    }

    /**
     * @return array
     */
    protected function provideOptions()
    {
        list($value1) = $this->provideOptionsValues();

        $option1 = new Product\Dto\Options();
        $option1->setCode(self::SAMPLE_ATTRIBUTE_CODE)
            ->setValues([$value1]);

        return [$option1];
    }

    /**
     * @return Extras[]
     */
    protected function provideExtras()
    {
        list($value1, $value2) = $this->provideExtraValues();
        $extra1 = new Product\Dto\Extras();
        $extra1->setCode(self::SAMPLE_EXTRA_CODE)
            ->setValues([$value1, $value2]);

        $extra2 = new Product\Dto\Extras();
        $extra2->setCode(self::SAMPLE_EXTRA_CODE_2)
            ->setValues([$value2]);

        return [$extra1, $extra2];
    }

    /**
     * @return array
     */
    protected function provideOptionsValues()
    {
        $value1 = new Product\Dto\Options\Values();
        $value1->setCode(self::SAMPLE_ATTRIBUTE_VALUE_CODE)
            ->setAdditionalPrice(5);

        return [$value1];
    }

    /**
     * @return array
     */
    protected function provideExtraValues()
    {
        $value1 = new Product\Dto\Options\Values();
        $value1->setCode(self::SAMPLE_EXTRA_VALUE_CODE)
            ->setAdditionalPrice(5);
        $value2 = new Product\Dto\Options\Values();
        $value2->setCode(self::SAMPLE_EXTRA_VALUE_CODE_2)
            ->setAdditionalPrice(10);

        return [$value1, $value2];
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
