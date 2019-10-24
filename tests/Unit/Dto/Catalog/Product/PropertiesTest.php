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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Catalog\Product;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Value as PropertyValue;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

class PropertiesTest extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     */
    public function testPropertiesEmptyValueInitializationLaterSetLocalization()
    {
        $propertyValue = new Dto\Properties\Value();
        $propertyValue->set('en-us', 'test');

        $this->assertEquals('test', $propertyValue->{'en-us'});
    }

    /**
     * @param string|array $value
     *
     * @dataProvider providePropertyValues
     *
     * @throws Exception
     */
    public function testPropertyValueInitialization($value)
    {
        $property = new Dto\Properties(['value' => $value]);

        $this->assertEquals($value, $property->getValue());
    }

    /**
     * @throws Exception
     */
    public function testTranslatedPropertyValueInitialization()
    {
        $locale = 'en-us';
        $values = ['red', 'green', 'blue'];
        $value = (new PropertyValue())->add($locale, $values);
        $property = new Dto\Properties(['value' => $value]);

        $this->assertEquals($value, $property->getValue());
        $this->assertInstanceOf(PropertyValue::class, $value);

        $property2 = new Dto\Properties(['value' => [$locale => $values]]);
        $this->assertEquals($values, $property2->getValue()->get($locale));
        $this->assertInstanceOf(PropertyValue::class, $value);
    }

    /**
     * @return array
     */
    public function providePropertyValues()
    {
        return [
            'simple value' => [
                'simple value',
            ],
            'array value' => [
                ['attributeValueCode1', 'attributeValueCode2'],
            ]
        ];
    }

    /**
     * @dataProvider providePropertyValues
     *
     * @throws Exception
     */
    public function testPropertyValueInitializationObject()
    {
        $property = new Dto\Properties(['value' => ['en-us' => 'value', 'de-de' => 'Wert']]);

        $this->assertEquals('value', $property->getValue()->{'en-us'});
        $this->assertEquals('Wert', $property->getValue()->{'de-de'});
    }

    /**
     * @throws Exception
     */
    public function testPropertyEmptyNameInitialization()
    {
        $name = (new Dto\Properties\Name())
            ->add('de-de', 'deutsch')
            ->add('en-us', 'english');

        $this->assertEquals('english', $name->{'en-us'});
    }

    /**
     * @throws Exception
     */
    public function testPropertySimpleNameInitialization()
    {
        $property = new Dto\Properties(['name' => 'Test Property']);

        $this->assertEquals('Test Property', $property->getName());
    }

    /**
     * @throws Exception
     */
    public function testPropertyTranslatedNameInitialization()
    {
        $property = new Dto\Properties(['name' => ['en-us' => 'Test Property']]);

        $this->assertEquals('Test Property', $property->getName()->{'en-us'});
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function testPropertyEmptySubDisplayGroupInitialization()
    {
        $subDisplayGroup = (new Dto\Properties\SubDisplayGroup())
            ->add('de-de', 'deutsch')
            ->add('en-us', 'english');

        $this->assertEquals('english', $subDisplayGroup->{'en-us'});
    }

    /**
     * @throws Exception
     */
    public function testPropertySimpleSubDisplayGroupInitialization()
    {
        $property = new Dto\Properties(['subDisplayGroup' => 'Test Display Group']);

        $this->assertEquals('Test Display Group', $property->getSubDisplayGroup());
    }

    /**
     * @throws Exception
     */
    public function testPropertyTranslatedSubDisplayGroupInitialization()
    {
        $property = new Dto\Properties(['subDisplayGroup' => ['en-us' => 'Test Display Group']]);

        $this->assertEquals('Test Display Group', $property->getSubDisplayGroup()->{'en-us'});
    }
}
