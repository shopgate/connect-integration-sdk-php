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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Customer;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Dto\Name;
use Shopgate\ConnectSdk\Dto\Customer\Attribute;
use Shopgate\ConnectSdk\Dto\Customer\AttributeValue;

use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Tests\Integration\CustomerTest;

class AttributeTest extends CustomerTest
{
    /**
     * @param int      $limit
     * @param int      $offset
     * @param int      $expectedAttributeCount
     * @param string[] $expectedAttributeCodes
     *
     * @throws Exception
     *
     * @dataProvider provideAttributeGetListLimitCases
     */
    public function testAttributeGetListLimit($limit, $offset, $expectedAttributeCount, $expectedAttributeCodes)
    {
        // Arrange
        $createdItemCount = 10;
        $sampleAttributes = $this->provideSampleAttributes($createdItemCount);

        // Act
        $this->createAttributes($sampleAttributes);

        $parameters = [];
        if (isset($limit)) {
            $parameters['limit'] = $limit;
        }
        if (isset($offset)) {
            $parameters['offset'] = $offset;
        }

        // Assert
        $attributes = $this->getAttributes($parameters);

        // CleanUp
        $deleteCodes = [];
        foreach ($attributes->getAttributes() as $attribute) {
            $deleteCodes[] = $attribute->getCode();
        }
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            $deleteCodes
        );

        $this->assertCount($expectedAttributeCount, $attributes->getAttributes());
        $this->assertEquals($expectedAttributeCodes, $deleteCodes);
        if (isset($limit)) {
            $this->assertEquals($limit, $attributes->getMeta()->getLimit());
        }
        if (isset($offset)) {
            $this->assertEquals($offset, $attributes->getMeta()->getOffset());
        }
    }

    /**
     * @throws Exception
     */
    public function testCreateAttributesDirect()
    {
        // Arrange
        $createdItemCount = 10;
        $sampleAttributes = $this->provideSampleAttributes($createdItemCount);

        // Act
        $this->createAttributes($sampleAttributes);

        // CleanUp
        $deleteCodes = [];
        $attributes  = $this->getAttributes();
        foreach ($attributes->getAttributes() as $attribute) {
            $deleteCodes[] = $attribute->getCode();
        }

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            $deleteCodes
        );
        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount($createdItemCount, $attributes->getAttributes());
    }

    /**
     * @throws Exception
     */
    public function testCreateAttributesEvent()
    {
        $this->markTestSkipped('Skipped - not working yet');

        // Arrange
        $createdItemCount = 10;
        $sampleAttributes = $this->provideSampleAttributes($createdItemCount);

        // Act
        $this->createAttributes($sampleAttributes);
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        $attributes = $this->getAttributes();

        // CleanUp
        $deleteCodes = [];
        foreach ($attributes->getAttributes() as $attribute) {
            $deleteCodes[] = $attribute->getCode();
        }
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            $deleteCodes
        );

        // Assert
        /** @noinspection PhpParamsInspection */
        $this->assertCount($createdItemCount, $attributes->getAttributes());
    }

    /**
     * @throws Exception
     */
    public function testGetAttributeDirect()
    {
        // Arrange
        $createdItemCount = 1;
        $sampleAttributes = $this->provideSampleAttributes($createdItemCount);

        // Act
        $this->createAttributes($sampleAttributes);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            ['code_1']
        );

        // Assert
        $attribute = $this->getAttribute('code_1');

        /** @noinspection PhpParamsInspection */
        $this->assertEquals('Attribute Name 1', $attribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testGetAttributeWithoutExistingAttribute()
    {
        $this->expectException(NotFoundException::class);

        // Act
        $this->sdk->getCustomerService()->getAttribute(
            'non_existing'
        );
    }

    /**
     * @throws Exception
     */
    public function testGetAttributeByLocaleDirect()
    {
        // Arrange
        $createdItemCount = 1;
        $sampleAttributes = $this->provideSampleAttributes($createdItemCount);

        // Act
        $this->createAttributes(
            $sampleAttributes,
            [
                'localeCode'  => 'en-us',
            ]
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            ['code_1']
        );

        // Assert
        $attribute = $this->getAttribute('code_1', 'en-us');

        /** @noinspection PhpParamsInspection */
        $this->assertEquals('Attribute Name 1', $attribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeDirect()
    {
        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1);
        $this->createAttributes($sampleAttributes);

        // Act
        $newName         = 'Renamed Attribute (Direct)';
        $attributeUpdate = new Attribute\Update(['name' => $newName]);
        $this->sdk->getCustomerService()->updateAttribute(
            'code_1',
            $attributeUpdate
        );

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            ['code_1']
        );

        // Assert
        $updatedAttribute = $this->getAttribute('code_1');
        $this->assertEquals($newName, $updatedAttribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeEvent()
    {
        $this->markTestSkipped('Skipped - not working yet');

        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1);
        $this->createAttributes($sampleAttributes);

        // Act
        $newName         = 'Renamed Attribute (Event)';
        $attributeUpdate = new Attribute\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $this->sdk->getCustomerService()->updateAttribute(
            'code_1',
            $attributeUpdate
        );

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_ATTRIBUTE,
            ['code_1']
        );

        // Assert
        $updatedAttribute = $this->getAttribute('code_1');
        $this->assertEquals($newName, $updatedAttribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeWithoutExistingAttribute()
    {
        // Assert
        $this->expectException(NotFoundException::class);

        // Act
        $this->sdk->getCustomerService()->updateAttribute(
            'non_existing',
            new Attribute\Update()
        );
    }

    /**
     * @throws Exception
     */
    public function testDeleteAttributeDirect()
    {
        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1);
        $this->createAttributes($sampleAttributes);

        // Act
        $this->sdk->getCustomerService()->deleteAttribute('code_1');

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        try {
            $this->getAttribute('code_1');
        } catch (NotFoundException $e) {
            $this->assertEquals($e->getMessage(), '{"code":"NotFound","message":"Attribute not found"}');
        }
    }

    /**
     * @throws Exception
     */
    public function testDeleteAttributeEvent()
    {
        $this->markTestSkipped('Skipped - not working yet');

        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1);
        $this->createAttributes($sampleAttributes);

        // Act
        $this->sdk->getCustomerService()->deleteAttribute('code_1');

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        try {
            $this->getAttribute('code_1');
        } catch (NotFoundException $e) {
            $this->assertEquals($e->getMessage(), '{"code":"NotFound","message":"Attribute not found"}');
        }
    }

    /**
     * @param array            $attributeData
     * @param RequestException $expectedException
     * @param string           $missingItem
     *
     * @throws Exception
     *
     * @dataProvider provideCreateAttributeWithMissingRequiredFields
     */
    public function testCreateAttributeDirectWithMissingRequiredFields(
        array $attributeData,
        $expectedException,
        $missingItem
    ) {
        // Arrange
        $attribute = new Attribute\Create($attributeData);

        // Act
        try {
            $this->createAttributes([$attribute]);
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
     * @return array
     */
    public function provideCreateAttributeWithMissingRequiredFields()
    {
        return [
            'missing name' => [
                'attributeData'     => [
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'isRequired' => true,
                    'code'       => 'code',
                    'values'     => [],
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'name',
            ],
            'missing type' => [
                'attributeData'     => [
                    'isRequired' => true,
                    'name'       => 'Name',
                    'code'       => 'code',
                    'values'     => [],
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'type',
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideAttributeGetListLimitCases()
    {
        return [
            'get the second'    => [
                'limit'                  => 1,
                'offset'                 => 1,
                'expectedCount'          => 1,
                'expectedAttributeCodes' => [
                    'code_10',
                ],
            ],
            'get the first'     => [
                'limit'                  => 1,
                'offset'                 => 0,
                'expectedCount'          => 1,
                'expectedAttributeCodes' => [
                    'code_1',
                ],
            ],
            'get two'           => [
                'limit'                  => 2,
                'offset'                 => 0,
                'expectedCount'          => 2,
                'expectedAttributeCodes' => [
                    'code_1',
                    'code_10',
                ],
            ],
            'limit 1'           => [
                'limit'                  => 1,
                'offset'                 => null,
                'expectedCount'          => 1,
                'expectedAttributeCodes' => [
                    'code_1',
                ],
            ],
            'limit 2'           => [
                'limit'                  => 2,
                'offset'                 => null,
                'expectedCount'          => 2,
                'expectedAttributeCodes' => [
                    'code_1',
                    'code_10',
                ],
            ],
            'offset 1'          => [
                'limit'                  => null,
                'offset'                 => 1,
                'expectedCount'          => 9,
                'expectedAttributeCodes' => [
                    'code_10',
                    'code_2',
                    'code_3',
                    'code_4',
                    'code_5',
                    'code_6',
                    'code_7',
                    'code_8',
                    'code_9',
                ],
            ],
            'offset 2'          => [
                'limit'                  => null,
                'offset'                 => 10,
                'expectedCount'          => 0,
                'expectedAttributeCodes' => [],
            ],
            'no entities found' => [
                'limit'                  => 1,
                'offset'                 => 10,
                'expectedCount'          => 0,
                'expectedAttributeCodes' => [],
            ],
        ];
    }

    /**
     * @param int $itemCount
     *
     * @return Attribute\Create[]
     */
    private function provideSampleAttributes($itemCount = 2)
    {
        $result = [];
        for ($count = 1; $count < ($itemCount + 1); $count++) {
            $attribute = new Attribute\Create();
            $attribute->setCode('code_' . $count)
                ->setType(Attribute\Create::TYPE_TEXT)
                ->setIsRequired(true)
                ->setName('Attribute Name ' . $count);

            $attributeValue = new AttributeValue\Create();
            $attributeValue->setCode('red_' . $count);
            $attributeValue->setSequenceId($count);
            $attributeValue->setName('Attribute Value Name' . $count);

            $attribute->setValues([$attributeValue]);

            $result[] = $attribute;
        }

        return $result;
    }

    /**
     * @param Attribute\Create[] $sampleAttributes
     * @param array              $meta
     *
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    private function createAttributes(array $sampleAttributes, array $meta = [])
    {
        return $this->sdk->getCustomerService()->addAttributes($sampleAttributes, $meta);
    }

    /**
     * @param array $meta
     *
     * @return Attribute\GetList
     * @throws Exception
     *
     */
    private function getAttributes($meta = [])
    {
        return $this->sdk->getCustomerService()->getAttributes($meta);
    }

    /**
     * @param string $attributeCode
     * @param string $localeCode
     *
     * @return Attribute\Get
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     */
    private function getAttribute($attributeCode, $localeCode = '')
    {
        return $this->sdk->getCustomerService()->getAttribute(
            $attributeCode,
            [
                'localeCode' => $localeCode,
            ]
        );
    }
}
