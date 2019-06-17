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

use Shopgate\ConnectSdk\DTO\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Categories;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Extras;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\LongDescription;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Media;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\MapPricing;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price\VolumePricing;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\ShortDescription;
use Shopgate\ConnectSdk\Exception\Exception;

class ProductTest extends CatalogTest
{
    /**
     * @throws Exception
     */
    public function testCreateProductMinimumDirect()
    {
        $this->markTestSkipped('Skipped due to catalog http code 500 issue when price tag isn\'t part of the product - the product is created even though');

        // Arrange
        $product = $this->prepareProductMinimum();

        // Act
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->code);
        $this->assertEquals($product->getCode(), $product->code);
    }

    /**
     * @throws Exception
     */
    public function testCreateProductMaximumDirect() {
        // Arrange
        $product = $this->prepareProductMaximum();

        $sampleCategories = $this->provideSampleCategories();
        $this->sdk->getCatalogService()->addCategories($sampleCategories, ['requestType' => 'direct']);
        $sampleCategoryCodes = $this->getCategoryCodes($sampleCategories);

        // Act
        $this->sdk->getCatalogService()->addProducts([$product], ['requestType' => 'direct']);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_PRODUCT, [$product->code]);
        $this->deleteEntitiesAfterTestRun(self::CATALOG_SERVICE, self::METHOD_DELETE_CATEGORY, $sampleCategoryCodes);

        // Assert
        $product = $this->sdk->getCatalogService()->getProduct($product->code);
        $this->assertEquals($product->getCode(), $product->code);
    }

    /**‚
     * Retrieves the default product with minimum details needed
     *
     * @return Product\Create
     */
    private function prepareProductMinimum()
    {
        $price = new Product\Dto\Price();
        $price->setPrice(90)
            ->setSalePrice(84.99)
            ->setCurrencyCode(Product\Dto\Price::CURRENCY_CODE_USD);
        $productPayload = new Product\Create();

        return $productPayload
            ->setCode('integration-test-product')
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setIsInventoryManaged(true);
    }

    /**
     * @return Categories[]
     */
    private function provideCategoryMapping()
    {
        $categoryMapping = new Product\Dto\Categories();
        $categoryMapping->setCode(self::CATEGORY_CODE)
            ->setIsPrimary(true);

        $categoryMapping2 = new Product\Dto\Categories();
        $categoryMapping2->setCode(self::CATEGORY_CODE_SECOND)
            ->setIsPrimary(false);
        return [$categoryMapping, $categoryMapping2];
    }

    private function provideIdentifiers()
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
    private function provideVolumePricing()
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
    private function provideMapPricing()
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
    private function providePricing() {
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
    private function provideSubDisplayGroup() {
        return (new Properties\SubDisplayGroup())
            ->add('de-de', 'deutsch')
            ->add('en-en', 'english');
    }

    /**
     * @todo-sg: unfinished
     */
    private function prepareProductMaximum()
    {
        $categories = $this->provideCategoryMapping();
        $identifiers = $this->provideIdentifiers();
        $price = $this->providePricing();
        $properties = $this->provideProperties();
        $shippingInformation = $this->provideShippingInformation();
        $media = $this->provideMedia();
        $options = $this->provideOptions();
        $extras = $this->provideExtras();

        $options = []; // not working, categories have to be set up first
        $extras = []; // not working, categories have to be set up first

        $name = new Product\Dto\Name();
        $name->add('en-us', 'Productname in english');
        $name->add('de-de', 'Produktname in deutsch');

        $longName = new Product\Dto\LongName();
        $longName->add('en-us', 'Long Productname in english');
        $longName->add('de-de', 'Long Produktname in deutsch');

        $productId = 'dfsdf25';

        return (new Product\Create())
            ->setName($name)
            ->setLongName($longName)
            ->setShortDescription(new ShortDescription(['en-us' => 'short description', 'de-de' => 'Kurzbeschreibung']))
            ->setLongDescription(new LongDescription(['en-us' => 'long description', 'de-de' => 'Beschreibung']))
            ->setCategories($categories)
            ->setProperties($properties)
            ->setMedia($media)
            ->setOptions($options)
            ->setExtras($extras)
            ->setCode($productId)// required
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
     * @return Product\Dto\Properties[]
     */
    private function provideProperties()
    {
        $subDisplayGroup = $this->provideSubDisplayGroup();

        $property1 = new Product\Dto\Properties\Product();
        $property1->setCode('property_code_1')
            ->setName(new Properties\Name(['en-us' => 'property 1 english', 'de-de' => 'property 1 deutsch']))
            ->setValue(new Properties\Value(['stuff' => 'stuff value', 'other stuff' => 'other stuff value']))
            ->setDisplayGroup(Properties::DISPLAY_GROUP_FEATURES)
            ->setSubDisplayGroup($subDisplayGroup);

        $property2 = (new Product\Dto\Properties\Simple())->setCode('property_code_2')
            ->setName(new Properties\Name(['en-us' => 'property 2 english', 'de-de' => 'property 2 deutsch']))
            ->setValue(new Properties\Value())
            ->setDisplayGroup('features')
            ->setSubDisplayGroup($subDisplayGroup);
        return [$property1, $property2];
    }

    /**
     * @return Product\Dto\ShippingInformation
     */
    private function provideShippingInformation()
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
    private function provideMedia()
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
    private function provideOptions()
    {
        list($value1, $value3) = $this->provideOptionsValues();

        $option1 = new Product\Dto\Options();
        $option1->setCode('option_1')
            ->setValues(
                [
                    $value1
                ]
            );

        $option2 = new Product\Dto\Options();
        $option2->setCode('option_2')
            ->setValues([$value3]);
        //$options = [$option1, $option2];
        $options = [$option1];

        return $options;
    }

    /**
     * @return Extras[]
     */
    private function provideExtras()
    {
        list($value1, $value2, $value3) = $this->provideOptionsValues();
        $extra1 = new Product\Dto\Extras();
        $extra1->setCode('extra_1')
            ->setValues([$value1, $value2]);

        $extra2 = new Product\Dto\Extras();
        $extra2->setCode('extra_2')
            ->setValues([$value3]);
        $extras = [$extra1, $extra2];
        return $extras;
    }

    /**
     * @return array
     */
    private function provideOptionsValues()
    {
        $value1 = new Product\Dto\Options\Values();
        $value1->setCode('code_value_1')
            ->setAdditionalPrice(5);
        $value2 = new Product\Dto\Options\Values();
        $value2->setCode('code_value_2')
            ->setAdditionalPrice(10);
        $value3 = new Product\Dto\Options\Values();
        $value3->setCode('code_value_3')
            ->setAdditionalPrice(50);
        return array($value1, $value2, $value3);
    }
}
