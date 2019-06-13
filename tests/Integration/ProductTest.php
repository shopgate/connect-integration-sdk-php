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

use Dto\Exceptions\InvalidDataTypeException;
use Shopgate\ConnectSdk\DTO\Catalog\Product;

class ProductTest extends ShopgateSdkTest
{
    public function testCreateProductMinimumDirect()
    {
        $this->markTestSkipped('Skipped due to being unfinished');
        $productPayload = $this->prepareProductMinimum();
        $this->sdk->catalog->createProduct($productPayload, ['requestType' => 'direct']);
        $product = $this->sdk->catalog->getProduct($productPayload->code);
        $this->assertEquals($product->getCode(), $productPayload->code);
    }

    /**
     * Retrieves the default product with minimum details needed
     *
     * @return Product\Create
     */
    private function prepareProductMinimum()
    {
        $price = new Product\Price();
        $price->setPrice(90)
              ->setSalePrice(84.99)
              ->setCurrencyCode(Product\Price::CURRENCY_CODE_USD);
        $productPayload = new Product\Create();

        return $productPayload->setCode('integration-test-product')
                              ->setCatalogCode('PNW Retail')
                              ->setName(new Product\Name(['en-us' => 'Blue Jeans regular']))
                              ->setStatus(Product\Create::STATUS_ACTIVE)
                              ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
                              ->setPrice($price)
                              ->setIsInventoryManaged(true);
    }

    /**
     * @todo-sg: unfinished
     * @throws InvalidDataTypeException
     */
    private function prepareProductMaximum()
    {
        $category1 = new Product\CategoryMapping();
        $category1->setCategoryCode('code_1')
                  ->setIsPrimary(true);

        $category2 = new Product\CategoryMapping();
        $category2->setCategoryCode('code_2')
                  ->setIsPrimary(false);
        $categories = [$category1, $category2];

        $identifiers = new Product\Identifiers();
        $identifiers->setMfgPartNum('someMfgPartNum')
                    ->setUpc('Universal-Product-Code')
                    ->setEan('European Article Number')
                    ->setIsbn('978-3-16-148410-0')
                    ->setSku('stock_keeping_unit');

        $volumePricing1 = new Product\VolumePricing();
        $volumePricing1->setMinQty(5)
                       ->setMaxQty(20)
                       ->setPrice(84.99)
                       ->setSalePrice(83.99)
                       ->setUnit('kg')
                       ->setPriceType(Product\VolumePricing::PRICE_TYPE_FIXED);

        $volumePricing2 = new Product\VolumePricing();
        $volumePricing2->setMinQty(21)
                       ->setMaxQty(100)
                       ->setPrice(84.99)
                       ->setSalePrice(-2)
                       ->setUnit('kg')
                       ->setPriceType(Product\VolumePricing::PRICE_TYPE_RELATIVE);
        $volumePricing = [$volumePricing1, $volumePricing2];

        $mapPricing1 = new Product\MapPricing();
        $mapPricing1->setStartDate('2019-06-01T00:00:00.000Z')
                    ->setEndDate('2019-09-01T00:00:00.000Z')
                    ->setPrice(84.49);

        $mapPricing2 = new Product\MapPricing();
        $mapPricing2->setStartDate('2019-06-01T00:00:00.000Z')
                    ->setEndDate('2019-09-01T00:00:00.000Z')
                    ->setPrice(84.49);
        $mapPricing = [$mapPricing1, $mapPricing2];

        $price = new Product\Price();
        $price->setCurrencyCode(Product\Price::CURRENCY_CODE_USD)
              ->setCost(50)
              ->setPrice(90)
              ->setSalePrice(84.99)
              ->setVolumePricing($volumePricing)
              ->setUnit('kg')
              ->setMsrp(100)
              ->setMinPrice(80)
              ->setMaxPrice(90)
              ->setMapPricing($mapPricing);

        $propertyValue1 =
            new Product\LocalizationValue(['stuff' => 'stuff value', 'other stuff' => 'other stuff value']);

        $subDisplayGroup = new Product\LocalizationSubDisplayGroup();
        $subDisplayGroup->add('de-de', 'deutsch');
        $subDisplayGroup->add('en-en', 'english');

        $property1 = new Product\Property();
        $property1->setCode('property_code_1')
                  ->setName('Property 1')
                  ->setValue($propertyValue1)
                  ->setType(Product\Property::TYPE_INPUT)
                  ->setDisplayGroup(Product\Property::DISPLAY_GROUP_GENERAL)
                  ->setSubDisplayGroup($subDisplayGroup);

        $propertyValue2 = new Product\LocalizationValue();

        $property2 = new Product\Property();
        $property2->setCode('property_code_2')
                  ->setName('Property 2')
                  ->setValue($propertyValue2)
                  ->setType(Product\Property::TYPE_OPTION)
                  ->setDisplayGroup(Property\Create::DISPLAY_GROUP_FEATURES)
                  ->setSubDisplayGroup($subDisplayGroup);
        $properties = [$property1, $property2];

        $shippingInformation = new Product\ShippingInformation();
        $shippingInformation->setIsShippedAlone(false)
                            ->setHeight(0.5)
                            ->setHeightUnit('m')
                            ->setWidth(10)
                            ->setWidthUnit('cm')
                            ->setLength(5)
                            ->setLengthUnit('dm')
                            ->setWeight(5)
                            ->setWeightUnit('kg');

        $media1 = new Product\Media();
        $media1->setCode('media_code_1')
               ->setType(Product\Media::TYPE_IMAGE)
               ->setUrl('example.com/media1.jpg')
               ->setAltText('alt text 1')
               ->setSubTitle('Title Media 1')
               ->setSequenceId(0);

        $media2 = new Product\Media();
        $media2->setCode('media_code_2')
               ->setType(Product\Media::TYPE_VIDEO)
               ->setUrl('example.com/media2.mov')
               ->setAltText('alt text 2')
               ->setSubTitle('Title Media 2')
               ->setSequenceId(5);

        $media = [$media1, $media2];

        $value1 = new Product\Value();
        $value1->setCode('code_value_1')
               ->setAdditionalPrice(5);
        $value2 = new Product\Value();
        $value2->setCode('code_value_2')
               ->setAdditionalPrice(10);
        $value3 = new Product\Value();
        $value3->setCode('code_value_3')
               ->setAdditionalPrice(50);

        $option1 = new Product\Option();
        $option1->setCode('option_1')
                ->setValues(
                    [
                        $value1
                        //                     , $value2
                    ]
                );

        $option2 = new Product\Option();
        $option2->setCode('option_2')
                ->setValues([$value3]);
        //$options = [$option1, $option2];
        $options = [$option1];

        $extra1 = new Product\Extra();
        $extra1->setCode('extra_1')
               ->setValues([$value1, $value2]);

        $extra2 = new Product\Extra();
        $extra2->setCode('extra_2')
               ->setValues([$value3]);
        $extras = [$extra1, $extra2];

        $categories = []; // not working, categories have to be set up first
        $options    = []; // not working, categories have to be set up first
        $extras     = []; // not working, categories have to be set up first

        $name = new Product\Name();
        $name->add('', 'abc');
        $name->add('abc', '');
        $name->add('asdfghi', 'asdfghi');
        $name->add('de-de', 'deutsch');

        $longName = new Product\LongName();

        $productId = 'dfsdf25';

        $productPayload = new Product\Create();
        $productPayload->setCode($productId)                                             // required
                       ->setParentProductCode('dfsdf7')
                       ->setModelType(Product\Create::MODEL_TYPE_STANDARD)                   // required
                       ->setFulfillmentMethods(['one method', 'another method'])
                       ->setUnit('kg')
                       ->setStatus(Product\Create::STATUS_ACTIVE)                            // required
                       ->setStartDate('2018-12-01T00:00:00.000Z')
                       ->setEndDate('2020-12-01T00:00:00.000Z')
                       ->setEolDate('2030-01-01T00:00:00.000Z')
                       ->setMinQty(1)
                       ->setMaxQty(100)
                       ->setIsInventoryManaged(true)                                     // required
                       ->setInventoryTreatment(Product\Create::INVENTORY_TREATMENT_PRE_ORDER)
                       ->setRating(3.5)
                       ->setUrl('test.com')
                       ->setFirstAvailableDate('2019-06-03T00:00:00.000Z')
                       ->setIsTaxed(true)
                       ->setTaxClass('f8c5c2e9')
                       ->setExternalUpdateDate('2019-06-01T00:00:00.000Z')
            //               ->setName($name)
            //               ->setLongName($longName)
                       ->setShippingInformation($shippingInformation)
                       ->setIdentifiers($identifiers)
                       ->setCatalogCode('PNW Retail')                                    // required
                       ->setCategories($categories)
                       ->setPrice($price)                                                // required
            //               ->setMedia($media)
                       ->setProperties($properties)
                       ->setOptions($options)
                       ->setExtras($extras)
                       ->setIsSerialized(false);
    }
}
