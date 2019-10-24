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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Catalog;

use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Tests\Integration\CatalogUtility;

class AttributeValueTest extends CatalogUtility
{

    /**
     * @throws Exception
     */
    public function testAddAttributeValueDirect()
    {
        // Arrange
        $this->createSampleAttribute();

        $newAttributeValue = new AttributeValue\Create();
        $newAttributeValue->setCode('color');
        $newAttributeValue->setSequenceId(2);

        $newAttributeValueName = new AttributeValue\Dto\Name();
        $newAttributeValueName->add('de-de', 'Attribute Value 2 de');
        $newAttributeValueName->add('en-us', 'Attribute Value 2 en');
        $newAttributeValue->setName($newAttributeValueName);

        $newAttributeValueSwatch = new AttributeValue\Dto\Swatch();
        $newAttributeValueSwatch->setType(AttributeValue::SWATCH_TYPE_COLOR);
        $newAttributeValueSwatch->setValue('blue');
        $newAttributeValue->setSwatch($newAttributeValueSwatch);

        // Act
        $this->sdk->getCatalogService()->addAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            [$newAttributeValue],
            ['requestType' => 'direct']
        );

        $addedAttributeValues = $this->sdk->getCatalogService()->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        /** @var AttributeValue\Get $addedAttributeValue */
        $addedAttributeValue = $addedAttributeValues[1];

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $addedAttributeValues);
        $this->assertEquals('Attribute Value 2 en', $addedAttributeValue->getName());
        $this->assertEquals('color', $addedAttributeValue->getSwatch()->getType());
        $this->assertEquals('blue', $addedAttributeValue->getSwatch()->getValue());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeValueDirect()
    {
        // Arrange
        $this->createSampleAttribute();

        $updateAttributeValue = new AttributeValue\Update();
        $updateAttributeValue->setCode('color_update');
        $updateAttributeValue->setSequenceId(2);

        $updateAttributeValueName = new AttributeValue\Dto\Name();
        $updateAttributeValueName->add('de-de', 'Attribute Value 2 de update');
        $updateAttributeValueName->add('en-us', 'Attribute Value 2 en update');
        $updateAttributeValue->setName($updateAttributeValueName);

        $updateAttributeValueSwatch = new AttributeValue\Dto\Swatch();
        $updateAttributeValueSwatch->setType(AttributeValue::SWATCH_TYPE_IMAGE);
        $updateAttributeValueSwatch->setValue('http://shopgate/image');
        $updateAttributeValue->setSwatch($updateAttributeValueSwatch);

        // Act
        $this->sdk->getCatalogService()->updateAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            self::SAMPLE_ATTRIBUTE_VALUE_CODE,
            $updateAttributeValue,
            ['requestType' => 'direct']
        );

        // Assert
        $updatedAttributeValues = $this->sdk->getCatalogService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        $updatedAttributeValue = $updatedAttributeValues[0];

        $this->assertCount(1, $updatedAttributeValues);
        $this->assertEquals('Attribute Value 2 en update', $updatedAttributeValue->getName());
        $this->assertEquals('image', $updatedAttributeValue->getSwatch()->getType());
        $this->assertEquals('http://shopgate/image', $updatedAttributeValue->getSwatch()->getValue());
    }


    /**
     * @throws Exception
     */
    public function testDeleteAttributeValueDirect()
    {
        // Arrange
        $this->createSampleAttribute();

        // Act
        $this->sdk->getCatalogService()->deleteAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            self::SAMPLE_ATTRIBUTE_VALUE_CODE,
            ['requestType' => 'direct']
        );

        // Assert
        $removedAttributeValues = $this->sdk->getCatalogService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        $this->assertCount(0, $removedAttributeValues);
    }

    /**
     * @throws Exception
     */
    public function testDeleteAttributeValueEvent()
    {
        $this->markTestSkipped('Skipped due to missing implementation in worker service');

        // Arrange
        $this->createSampleAttribute();

        // Act
        $this->sdk->getCatalogService()->deleteAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            self::SAMPLE_ATTRIBUTE_VALUE_CODE
        );

        usleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $removedAttributeValues = $this->sdk->getCatalogService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        $this->assertCount(0, $removedAttributeValues);
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeValueWithoutExistingAttributeValue()
    {
        // Arrange
        $this->createSampleAttribute();

        $updateAttributeValue = new AttributeValue\Update();
        $updateAttributeValue->setCode('color_update');
        $updateAttributeValue->setSequenceId(2);

        $updateAttributeValueName = new AttributeValue\Dto\Name();
        $updateAttributeValueName->add('de-de', 'Attribute Value 2 de update');
        $updateAttributeValueName->add('en-us', 'Attribute Value 2 en update');
        $updateAttributeValue->setName($updateAttributeValueName);

        $updateAttributeValueSwatch = new AttributeValue\Dto\Swatch();
        $updateAttributeValueSwatch->setType(AttributeValue::SWATCH_TYPE_IMAGE);
        $updateAttributeValueSwatch->setValue('http://shopgate/image');
        $updateAttributeValue->setSwatch($updateAttributeValueSwatch);

        // Act
        try {
            $this->sdk->getCatalogService()->updateAttributeValue(
                self::SAMPLE_ATTRIBUTE_CODE,
                'non_existing',
                $updateAttributeValue,
                ['requestType' => 'direct']
            );
        } catch (NotFoundException $exception) {
            // Assert
            $this->assertEquals(
                '{"code":"NotFound","message":"Attribute value not found."}',
                $exception->getMessage()
            );

            return;
        }

        $this->fail('Expected RequestException but wasn\'t thrown');
    }

    /**
     * @param array     $attributeValueData
     * @param Exception $expectedException
     * @param string    $missingItem
     *
     * @throws Exception
     *
     * @dataProvider provideAddAttributeValueWithMissingRequiredFields
     */
    public function testAddAttributeValueDirectWithMissingRequiredFields(
        array $attributeValueData,
        $expectedException,
        $missingItem
    ) {
        // Arrange
        $this->createSampleAttribute();
        $attributeValue = new AttributeValue\Create($attributeValueData);

        // Act
        try {
            $this->sdk->getCatalogService()->addAttributeValue(
                self::SAMPLE_ATTRIBUTE_CODE,
                [$attributeValue],
                [
                    'requestType' => 'direct',
                ]
            );
        } catch (RequestException $exception) {
            // Assert
            $errors = \GuzzleHttp\json_decode($exception->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals('Missing required property: ' . $missingItem, $message);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        } catch (InvalidDataTypeException $exception) {
            $this->assertInstanceOf(get_class($expectedException), $exception);

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @param array     $attributeValueData
     * @param Exception $expectedException
     * @param string    $expectedMessage
     *
     * @throws Exception
     *
     * @dataProvider provideAddAttributeValuesWithInvalidFields
     */
    public function testAddAttributeValueDirectWithInvalidFields(
        array $attributeValueData,
        Exception $expectedException,
        $expectedMessage
    ) {
        // Arrange
        $this->createSampleAttribute();

        // Act
        try {
            $attributeValue = new AttributeValue\Create($attributeValueData);
            $this->sdk->getCatalogService()->addAttributeValue(
                self::SAMPLE_ATTRIBUTE_CODE,
                [$attributeValue],
                [
                    'requestType' => 'direct',
                ]
            );
        } catch (RequestException $requestException) {
            // Assert
            $errors = \GuzzleHttp\json_decode($requestException->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;

            $this->assertInstanceOf(get_class($expectedException), $requestException);
            $this->assertEquals($expectedMessage, $message);
            $this->assertEquals($expectedException->getStatusCode(), $requestException->getStatusCode());

            return;
        } catch (InvalidDataTypeException $invalidDataTypeException) {
            $this->assertInstanceOf(get_class($expectedException), $invalidDataTypeException);

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function provideAddAttributeValueWithMissingRequiredFields()
    {
        $name = new AttributeValue\Dto\Name();
        $name->add('de-de', 'Example');
        $swatch = new AttributeValue\Dto\Swatch();

        return [
            'missing code' => [
                'attributeValueData' => [
                    'sequenceId' => 1006,
                    'swatch' => $swatch,
                    'name' => $name,
                ],
                'expectedException'  => new RequestException(400),
                'missingItem'        => 'code',
            ],
            'missing sequenceId' => [
                'attributeData' => [
                    'code' => 'code',
                    'name' => $name,
                    'swatch' => $swatch,

                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'sequenceId',
            ],
            'missing name' => [
                'attributeData' => [
                    'code' => 'code',
                    'sequenceId' => 1006,
                    'swatch' => $swatch,

                ],
                'expectedException' => new RequestException(400),
                'missingItem' => 'name',
            ],
        ];
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function provideAddAttributeValuesWithInvalidFields()
    {
        $name = new AttributeValue\Dto\Name();
        $name->add('de-de', 'Example');
        $swatch = new AttributeValue\Dto\Swatch();

        return [
            'invalid code' => [
                'attributeValueData' => [
                    'code' => 1234,
                    'sequenceId' => 1006,
                    'swatch' => $swatch,
                    'name' => $name,
                ],
                'expectedException' => new InvalidDataTypeException(),
                'message' => '',
            ],
            'invalid sequenceId' => [
                'attributeValueData' => [
                    'code' => 'code',
                    'sequenceId' => 'INVALID',
                    'swatch' => $swatch,
                    'name' => $name,
                ],
                'expectedException' => new InvalidDataTypeException(),
                'message' => '',
            ],
            'invalid name' => [
                'attributeValueData' => [
                    'code' => 'code',
                    'sequenceId' => 1006,
                    'swatch' => $swatch,
                    'name' => 'INVALID',
                ],
                'expectedException' => new RequestException(400),
                'message' => 'Expected type object but found type array',
            ],
            'invalid swatch' => [
                'attributeValueData' => [
                    'code' => 'code',
                    'sequenceId' => 1006,
                    'swatch' => 'INVALID',
                    'name' => $name,
                ],
                'expectedException' => new RequestException(400),
                'message' => 'Expected type object but found type array',
            ],
        ];
    }
}
