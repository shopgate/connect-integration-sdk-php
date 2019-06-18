<?php
/**
 * Created by PhpStorm.
 * User: alexanderwesselburg
 * Date: 18.06.19
 * Time: 09:22
 */

namespace Shopgate\ConnectSdk\Tests\Integration;

use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Exception\RequestException;

class AttributeValueTest extends ShopgateSdkTest
{
    const SAMPLE_ATTRIBUTE_CODE       = 'attribute_code_1';
    const SAMPLE_ATTRIBUTE_VALUE_CODE = 'attribute_value_code_1';

    /**
     * @throws Exception
     */
    public function tearDown()
    {
        $this->sdk->getCatalogService()->deleteAttribute(self::SAMPLE_ATTRIBUTE_CODE, ['requestType' => 'direct']);
        parent::tearDown();
    }

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

        // Assert
        $addedAttributeValues = $this->sdk->getCatalogService()->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        /** @var AttributeValue\Get $addedAttributeValue */
        $addedAttributeValue  = $addedAttributeValues[1];

        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $addedAttributeValues);
        $this->assertEquals('Attribute Value 2 en', $addedAttributeValue->getName());
        $this->assertEquals('color', $addedAttributeValue->getSwatch()->getType());
        $this->assertEquals('blue', $addedAttributeValue->getSwatch()->getValue());
    }

    /**
     * @throws Exception
     */
    public function testAddAttributeValueEvent()
    {
        $this->markTestSkipped('Skipped due to bug in worker service');

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
            [$newAttributeValue]
        );

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $addedAttributeValues = $this->sdk->getCatalogService()->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        $addedAttributeValue  = $addedAttributeValues[1];

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
        $updatedAttributeValue  = $updatedAttributeValues[0];

        $this->assertCount(1, $updatedAttributeValues);
        $this->assertEquals('Attribute Value 2 en update', $updatedAttributeValue->getName());
        $this->assertEquals('image', $updatedAttributeValue->getSwatch()->getType());
        $this->assertEquals('http://shopgate/image', $updatedAttributeValue->getSwatch()->getValue());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeValueEvent()
    {
        $this->markTestSkipped('Skipped due to bug in worker service');

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
            $updateAttributeValue
        );

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $updatedAttributeValues = $this->sdk->getCatalogService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();
        $updatedAttributeValue  = $updatedAttributeValues[0];

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
        $this->markTestSkipped('Skipped due to bug in worker service');

        // Arrange
        $this->createSampleAttribute();

        // Act
        $this->sdk->getCatalogService()->deleteAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            self::SAMPLE_ATTRIBUTE_VALUE_CODE
        );

        sleep(self::SLEEP_TIME_AFTER_EVENT);

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
        } catch (RequestException $exception) {
            // Assert
            $this->assertEquals(
                '{"code":"NotFound","message":"Attribute value not found."}',
                $exception->getMessage()
            );
            $this->assertEquals(404, $exception->getStatusCode());

            return;
        }

        $this->fail('Expected RequestException but wasn\'t thrown');
    }

    /**
     * @param array            $attributeValueData
     * @param RequestException $expectedException
     * @param string           $missingItem
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
            $errors  = \GuzzleHttp\json_decode($exception->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals('Missing required property: ' . $missingItem, $message);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * @param array            $attributeValueData
     * @param RequestException $expectedException
     * @param string           $expectedMessage
     *
     * @throws Exception
     *
     * @dataProvider provideAddAttributeValuesWithInvalidFields
     */
    public function testAddAttributeValueDirectWithInvalidFields(
        array $attributeValueData,
        RequestException $expectedException,
        $expectedMessage
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
            $errors  = \GuzzleHttp\json_decode($exception->getMessage(), false);
            $message = $errors->error->results->errors[0]->message;
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals($expectedMessage, $message);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but wasn\'t thrown');
    }

    /**
     * Create an attribute
     */
    public function createSampleAttribute()
    {
        $attribute = new Attribute\Create();
        $attribute->setCode(self::SAMPLE_ATTRIBUTE_CODE)
            ->setType(Attribute\Create::TYPE_TEXT)
            ->setUse(Attribute\Create::USE_OPTION)
            ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');

        $attributeName = new Name();
        $attributeName->add('de-de', 'Attribute de');
        $attributeName->add('en-us', 'Attribute en');
        $attribute->setName($attributeName);

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

        $attribute->setValues([$attributeValue]);

        $this->sdk->getCatalogService()->addAttributes([$attribute], ['requestType' => 'direct']);
    }

    /**
     * @return array
     */
    public function provideAddAttributeValueWithMissingRequiredFields()
    {
        $name = new AttributeValue\Dto\Name();
        $name->add('de-de', 'Example');
        $swatch = new AttributeValue\Dto\Swatch();

        return [
            'missing code'       => [
                'attributeValueData' => [
                    'sequenceId' => 1006,
                    'swatch'     => $swatch,
                    'name'       => $name,
                ],
                'expectedException'  => new RequestException(400),
                'missingItem'        => 'code',
            ],
            'missing sequenceId' => [
                'attributeData'     => [
                    'code'   => 'code',
                    'name'   => $name,
                    'swatch' => $swatch,

                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'sequenceId',
            ],
            'missing name'       => [
                'attributeData'     => [
                    'code'       => 'code',
                    'sequenceId' => 1006,
                    'swatch'     => $swatch,

                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'name',
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideAddAttributeValuesWithInvalidFields()
    {
        $name = new AttributeValue\Dto\Name();
        $name->add('de-de', 'Example');
        $swatch = new AttributeValue\Dto\Swatch();

        return [
            /*
             * @todo check - currently not working
            'invalid code' => [
                'attributeValueData' => [
                    'code'       => 1234,
                    'sequenceId' => 1006,
                    'swatch'     => $swatch,
                    'name'       => $name,
                ],
                'expectedException'  => new RequestException(400),
                'message'            => 'Expected type object but found type array',
            ],
            */
            /*
             * @todo check - currently not working
            'invalid sequenceId' => [
                'attributeValueData' => [
                    'code'       => 'code',
                    'sequenceId' => 'INVALID',
                    'swatch'     => $swatch,
                    'name'       => $name,
                ],
                'expectedException'  => new RequestException(400),
                'message'            => 'Expected type object but found type array',
            ],
            */
            /*
             * @todo check - currently not working
            'invalid name' => [
                'attributeValueData' => [
                    'code'       => 'code',
                    'sequenceId' => 1006,
                    'swatch'     => $swatch,
                    'name'       => 'INVALID',
                ],
                'expectedException'  => new RequestException(400),
                'message'            => 'Expected type object but found type array',
            ],
            */
            /*
             * @todo check - currently not working
            'invalid swatch' => [
                'attributeValueData' => [
                    'code'       => 'code',
                    'sequenceId' => 1006,
                    'swatch'     => 'INVALID',
                    'name'       => $name,
                ],
                'expectedException'  => new RequestException(400),
                'message'            => 'Expected type object but found type array',
            ],
            */
        ];
    }
}
