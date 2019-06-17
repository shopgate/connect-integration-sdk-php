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
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Media;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;
use Shopgate\ConnectSdk\Exception\Exception;

class ProductTest extends ShopgateSdkTest
{
    const PRODUCT_CODE = 'integration-test';

    private $cleanUpProductCodes = [];

    /**
     * Runs before every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanUpProductCodes = [];
    }

    /**
     * @throws Exception
     */
    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->cleanUpProductCodes as $productCode) {
            $this->sdk->getCatalogService()->deleteProduct(
                $productCode, [
                    'requestType' => 'direct'
                ]
            );
        }
    }
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
        $this->cleanUpProductCodes[] = $product->code;

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
            ->setIsInventoryManaged(true)
        ;
    }

    /**
     * @todo-sg: unfinished
     * @throws InvalidDataTypeException
     */
    private function prepareProductMaximum()
    {
        $categoryMapping = new Product\Dto\Categories();
        $categoryMapping->setCode('code_1')
            ->setIsPrimary(true);

        $categoryMapping2 = new Product\Dto\Categories();
        $categoryMapping2->setCode('code_2')
            ->setIsPrimary(false);
        $categories = [$categoryMapping, $categoryMapping2];

        $identifiers = new Product\Dto\Identifiers();
        $identifiers->setMfgPartNum('someMfgPartNum')
            ->setUpc('Universal-Product-Code')
            ->setEan('European Article Number')
            ->setIsbn('978-3-16-148410-0')
            ->setSku('stock_keeping_unit');

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
        $volumePricing = [$volumePricing1, $volumePricing2];

        $mapPricing1 = new Product\Dto\Price\MapPricing();
        $mapPricing1->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(84.49);

        $mapPricing2 = new Product\Dto\Price\MapPricing();
        $mapPricing2->setStartDate('2019-06-01T00:00:00.000Z')
            ->setEndDate('2019-09-01T00:00:00.000Z')
            ->setPrice(84.49);
        $mapPricing = [$mapPricing1, $mapPricing2];

        $price = new Product\Dto\Price();
        $price->setCurrencyCode(Product\Dto\Price::CURRENCY_CODE_USD)
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
            new Properties\Value(['stuff' => 'stuff value', 'other stuff' => 'other stuff value']);

        $subDisplayGroup = new Properties\SubDisplayGroup();
        $subDisplayGroup->add('de-de', 'deutsch');
        $subDisplayGroup->add('en-en', 'english');

        $property1 = new Product\Dto\Properties\Input();
        $property1->setCode('property_code_1')
            ->setName(new Properties\Name(['en-us' => 'product name in englisch', 'de-de' => 'Produktname in deutsch']))
            ->setValue($propertyValue1)
            ->setDisplayGroup(Properties::DISPLAY_GROUP_FEATURES)
            ->setSubDisplayGroup($subDisplayGroup);

        $propertyValue2 = new Properties\Value();

        $property2 = new Product\Dto\Properties\Simple();
        $property2->setCode('property_code_2')
            ->setName(new Properties\Name('Property 2'))
            ->setValue($propertyValue2)
            ->setDisplayGroup('features')
            ->setSubDisplayGroup($subDisplayGroup);
        $properties = [$property1, $property2];

        $shippingInformation = new Product\Dto\ShippingInformation();
        $shippingInformation->setIsShippedAlone(false)
            ->setHeight(0.5)
            ->setHeightUnit('m')
            ->setWidth(10)
            ->setWidthUnit('cm')
            ->setLength(5)
            ->setLengthUnit('dm')
            ->setWeight(5)
            ->setWeightUnit('kg');

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

        $value1 = new Product\Dto\Options\Values();
        $value1->setCode('code_value_1')
            ->setAdditionalPrice(5);
        $value2 = new Product\Dto\Options\Values();
        $value2->setCode('code_value_2')
            ->setAdditionalPrice(10);
        $value3 = new Product\Dto\Options\Values();
        $value3->setCode('code_value_3')
            ->setAdditionalPrice(50);

        $option1 = new Product\Dto\Options();
        $option1->setCode('option_1')
            ->setValues(
                [
                    $value1
                    //                     , $value2
                ]
            );

        $option2 = new Product\Dto\Options();
        $option2->setCode('option_2')
            ->setValues([$value3]);
        //$options = [$option1, $option2];
        $options = [$option1];

        $extra1 = new Product\Dto\Extras();
        $extra1->setCode('extra_1')
            ->setValues([$value1, $value2]);

        $extra2 = new Product\Dto\Extras();
        $extra2->setCode('extra_2')
            ->setValues([$value3]);
        $extras = [$extra1, $extra2];

        $categories = []; // not working, categories have to be set up first
        $options = []; // not working, categories have to be set up first
        $extras = []; // not working, categories have to be set up first

        $name = new Product\Dto\Name();
        $name->add('', 'abc');
        $name->add('abc', '');
        $name->add('asdfghi', 'asdfghi');
        $name->add('de-de', 'deutsch');

        $longName = new Product\Dto\LongName();

        $productId = 'dfsdf25';

        $productPayload = new Product\Create();
        $productPayload->setCode($productId)// required
        ->setParentProductCode('dfsdf7')
            ->setCategories($categories)
            ->setMedia($media)
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)// required
            ->setFulfillmentMethods(['one method', 'another method'])
            ->setUnit('kg')
            ->setStatus(Product\Create::STATUS_ACTIVE)// required
            ->setStartDate('2018-12-01T00:00:00.000Z')
            ->setEndDate('2020-12-01T00:00:00.000Z')
            ->setEolDate('2030-01-01T00:00:00.000Z')
            ->setMinQty(1)
            ->setMaxQty(100)
            ->setIsInventoryManaged(true)// required
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
            ->setCatalogCode('PNW Retail')// required
            ->setCategories($categories)
            ->setPrice($price)// required
            //               ->setMedia($media)
            ->setProperties($properties)
            ->setOptions($options)
            ->setExtras($extras)
            ->setIsSerialized(false);
    }
}
