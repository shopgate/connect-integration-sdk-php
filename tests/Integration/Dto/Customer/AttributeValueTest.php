<?php
/**
 * Created by PhpStorm.
 * User: alexanderwesselburg
 * Date: 26.06.19
 * Time: 14:34
 */

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Customer;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Customer\Attribute;
use Shopgate\ConnectSdk\Dto\Customer\AttributeValue;

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Tests\Integration\CustomerTest;

class AttributeValueTest extends CustomerTest
{
    const SAMPLE_ATTRIBUTE_CODE = 'attribute_code_1';
    const SAMPLE_ATTRIBUTE_VALUE_CODE = 'attribute_value_code_1';

    /**
     * @throws Exception
     */
    public function testAddAttributeValueDirect()
    {
        // Arrange
        $this->createSampleAttribute();

        $newAttributeValue = new AttributeValue\Create();
        $newAttributeValue->setCode('new color');
        $newAttributeValue->setSequenceId(2);
        $newAttributeValue->setName('new name');

        // Act
        $this->sdk->getCustomerService()->addAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            [$newAttributeValue],
            ['requestType' => 'direct']
        );

        $addedAttributeValues = $this->sdk->getCustomerService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();

        /** @var AttributeValue\Get $addedAttributeValue */
        $addedAttributeValue = $addedAttributeValues[1];

        // Prepare delete
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            [self::SAMPLE_ATTRIBUTE_CODE]
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount(2, $addedAttributeValues);
        $this->assertEquals('new name', $addedAttributeValue->getName());
        $this->assertEquals('new color', $addedAttributeValue->getCode());
        $this->assertEquals(2, $addedAttributeValue->getSequenceId());
    }

    public function testUpdateAttributeValueDirect()
    {
        // Arrange
        $this->createSampleAttribute();

        $updateAttributeValue = new AttributeValue\Update();
        $updateAttributeValue->setSequenceId(8);
        $updateAttributeValue->setName('update name');

        // Act
        $this->sdk->getCustomerService()->updateAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            self::SAMPLE_ATTRIBUTE_VALUE_CODE,
            $updateAttributeValue,
            ['requestType' => 'direct']
        );

        // Prepare delete
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            [self::SAMPLE_ATTRIBUTE_CODE]
        );


        $addedAttributeValues = $this->sdk->getCustomerService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();

        /** @var AttributeValue\Get $addedAttributeValue */
        $addedAttributeValue = $addedAttributeValues[0];

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount(1, $addedAttributeValues);
        $this->assertEquals('update name', $addedAttributeValue->getName());
        $this->assertEquals(8, $addedAttributeValue->getSequenceId());
    }

    /**
     * @throws Exception
     */
    public function testDeleteAttributeValueDirect()
    {
        // Arrange
        $this->createSampleAttribute();

        $newAttributeValue = new AttributeValue\Create();
        $newAttributeValue->setCode('to_delete');
        $newAttributeValue->setSequenceId(2);
        $newAttributeValue->setName('to delete name');

        $this->sdk->getCustomerService()->addAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            [$newAttributeValue],
            ['requestType' => 'direct']
        );

        // Act
        $this->sdk->getCustomerService()->deleteAttributeValue(
            self::SAMPLE_ATTRIBUTE_CODE,
            'to_delete',
            ['requestType' => 'direct']
        );

        // Prepare delete
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            [self::SAMPLE_ATTRIBUTE_CODE]
        );

        // Assert
        $removedAttributeValues = $this->sdk->getCustomerService()
            ->getAttribute(self::SAMPLE_ATTRIBUTE_CODE)->getValues();

        $this->assertCount(1, $removedAttributeValues);
    }

    /**
     *
     * @return Attribute\Create[]
     */
    private function createSampleAttribute()
    {
        $attribute = new Attribute\Create();
        $attribute->setCode(self::SAMPLE_ATTRIBUTE_CODE)
            ->setType(Attribute\Create::TYPE_TEXT)
            ->setIsRequired(true)
            ->setName('Name');

        $attributeValue = new AttributeValue\Create();
        $attributeValue->setCode(self::SAMPLE_ATTRIBUTE_VALUE_CODE);
        $attributeValue->setSequenceId(1);
        $attributeValue->setName('Attribute Value Name');

        $attribute->setValues([$attributeValue]);

        $this->sdk->getCustomerService()->addAttributes([$attribute], ['requestType' => 'direct']);
    }
}
