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

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;

class AttributeTest extends ShopgateSdkTest
{
    /** @var array */
    protected $cleanUpAttributeCodes = [];

    /**
     * @throws Exception
     */
    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->cleanUpAttributeCodes as $attributeCode) {
            $this->sdk->getCatalogService()->deleteAttribute(
                $attributeCode,
                [
                    'requestType' => 'direct',
                ]
            );
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
        $this->createAttributes(
            $sampleAttributes,
            [
                'requestType' => 'direct',
            ]
        );

        // Assert
        $attributes = $this->getAttributes();

        /** @noinspection PhpParamsInspection */
        $this->assertCount($createdItemCount, $attributes->getAttributes());
    }

    /**
     * @throws Exception
     */
    public function testCreateAttributesEvent()
    {
        $this->markTestSkipped('Skipped due to bug in worker service');

        // Arrange
        $createdItemCount = 10;
        $sampleAttributes = $this->provideSampleAttributes($createdItemCount);

        // Act
        $this->createAttributes($sampleAttributes);
        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $attributes = $this->getAttributes();

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
        $this->createAttributes(
            $sampleAttributes,
            [
                'requestType' => 'direct',
            ]
        );

        // Assert
        $attribute = $this->getAttribute('code_1');

        /** @noinspection PhpParamsInspection */
        $this->assertEquals('Attribute 1 en', $attribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testGetAttributeWithoutExistingAttribute()
    {
        // Act
        try {
            $this->sdk->getCatalogService()->getAttribute(
                'non_existing'
            );
        } catch (RequestException $exception) {
            // Assert
            $this->assertEquals(404, $exception->getStatusCode());

            return;
        }
        $this->fail('Expected RequestException but wasn\'t thrown');
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
                'requestType' => 'direct',
            ]
        );

        // Assert
        $attribute = $this->getAttribute('code_1', 'de-de');

        /** @noinspection PhpParamsInspection */
        $this->assertEquals('Attribute 1 de', $attribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeDirect()
    {
        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1);
        $this->createAttributes(
            $sampleAttributes,
            [
                'requestType' => 'direct',
            ]
        );

        // Act
        $newName         = 'Renamed Attribute (Direct)';
        $attributeUpdate = new Attribute\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $this->sdk->getCatalogService()->updateAttribute(
            'code_1',
            $attributeUpdate,
            [
                'requestType' => 'direct',
            ]
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
        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1);
        $this->createAttributes(
            $sampleAttributes,
            [
                'requestType' => 'direct',
            ]
        );

        // Act
        $newName         = 'Renamed Attribute (Event)';
        $attributeUpdate = new Attribute\Update(['name' => new Name(['en-us' => $newName])]);

        // Act
        $this->sdk->getCatalogService()->updateAttribute(
            'code_1',
            $attributeUpdate
        );

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        $updatedAttribute = $this->getAttribute('code_1');
        $this->assertEquals($newName, $updatedAttribute->getName());
    }

    /**
     * @throws Exception
     */
    public function testUpdateAttributeWithoutExistingAttribute()
    {
        // Act
        try {
            $this->sdk->getCatalogService()->updateAttribute(
                'non_existing',
                new Attribute\Update(),
                [
                    'requestType' => 'direct',
                ]
            );
        } catch (RequestException $exception) {
            // Assert
            $this->assertEquals(404, $exception->getStatusCode());

            return;
        }
        $this->fail('Expected RequestException but wasn\'t thrown');
    }

    /**
     * @throws Exception
     */
    public function testDeleteAttributeDirect()
    {
        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1, false);
        $this->createAttributes(
            $sampleAttributes,
            [
                'requestType' => 'direct',
            ]
        );

        // Act
        $this->sdk->getCatalogService()->deleteAttribute(
            'code_1',
            [
                'requestType' => 'direct',
            ]
        );

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        try {
            $this->getAttribute('code_1');
        } catch (RequestException $e) {
            unset($this->cleanUpAttributeCodes['code_1']);
            $this->assertEquals($e->getMessage(), '{"code":"NotFound","message":"Attribute not found"}');
        }
    }

    /**
     * @throws Exception
     */
    public function testDeleteAttributeEvent()
    {
        // Arrange
        $sampleAttributes = $this->provideSampleAttributes(1, false);
        $this->createAttributes(
            $sampleAttributes,
            [
                'requestType' => 'direct',
            ]
        );

        // Act
        $this->sdk->getCatalogService()->deleteAttribute('code_1');

        sleep(self::SLEEP_TIME_AFTER_EVENT);

        // Assert
        try {
            $this->getAttribute('code_1');
        } catch (RequestException $e) {
            unset($this->cleanUpAttributeCodes['code_1']);
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
            $this->createAttributes(
                [$attribute],
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
     * @param array            $attributeData
     * @param RequestException $expectedException
     * @param string           $expectedMessage
     *
     * @throws Exception
     *
     * @dataProvider provideCreateAttributeWithInvalidFields
     */
    public function testCreateAttributeDirectWithInvalidFields(
        array $attributeData,
        RequestException $expectedException,
        $expectedMessage
    ) {
        // Arrange
        $attribute = new Attribute\Create($attributeData);

        // Act
        try {
            $this->createAttributes(
                [$attribute],
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
     * @return array
     */
    public function provideCreateAttributeWithMissingRequiredFields()
    {
        $name = new Attribute\Dto\Name();
        $name->add('de-de', 'Example');

        return [
            'missing name'   => [
                'attributeData'     => [
                    'values'     => [],
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'name',
            ],
            'missing values' => [
                'attributeData'     => [
                    'name'       => $name,
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'values',
            ],
            'missing use'    => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => [],
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'use',
            ],
            'missing type'   => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => [],
                    'use'        => Attribute\Create::USE_OPTION,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'type',
            ],
            'missing code'   => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => [],
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'missingItem'       => 'code',
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideCreateAttributeWithInvalidFields()
    {
        $name = new Attribute\Dto\Name();
        $name->add('de-de', 'Example');

        return [
            /*
             * @todo check - currently not working
            'invalid name' => [
                'attributeData'     => [
                    'name'       => 'INVALID',
                    'values'     => [],
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'message'           => 'Expected type object but found type array',
            ],
            */
            /*
             * @todo check - currently not working
            'invalid values' => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => 'invalid',
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'expected'       => 'array',
            ],
            */
            'invalid use'  => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => [],
                    'use'        => 'INVALID',
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'message'           => 'No enum match for: INVALID',
            ],
            'invalid type' => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => [],
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => 'INVALID',
                    'code'       => 'code',
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'message'           => 'No enum match for: INVALID',
            ],
            /*
             * @todo check - currently not working
            'invalid code'   => [
                'attributeData'     => [
                    'name'       => $name,
                    'values'     => [],
                    'use'        => Attribute\Create::USE_OPTION,
                    'type'       => Attribute\Create::TYPE_TEXT,
                    'code'       => [],
                    'sequenceId' => 1006,
                ],
                'expectedException' => new RequestException(400),
                'message'       => null,
            ],
            */
        ];
    }

    /**
     * @param int  $itemCount
     * @param bool $removeOnTearDown
     *
     * @return Attribute\Create[]
     */
    private function provideSampleAttributes($itemCount = 2, $removeOnTearDown = true)
    {
        $result = [];
        for ($count = 1; $count < ($itemCount + 1); $count++) {
            $attribute = new Attribute\Create();
            $attribute->setCode('code_' . $count)
                ->setType(Attribute\Create::TYPE_TEXT)
                ->setUse(Attribute\Create::USE_OPTION);

            $attributeName = new Name();
            $attributeName->add('de-de', 'Attribute ' . $count . ' de');
            $attributeName->add('en-us', 'Attribute ' . $count . ' en');
            $attribute->setName($attributeName);

            if (!$minimal) {
                $attribute->setExternalUpdateDate('2018-12-15T00:00:23.114Z');

                $attributeValue = new AttributeValue\Create();
                $attributeValue->setCode('red');
                $attributeValue->setSequenceId($count);

                $attributeValueName = new AttributeValue\Dto\Name();
                $attributeValueName->add('de-de', 'Attribute Value ' . $count . ' de');
                $attributeValueName->add('en-us', 'Attribute Value ' . $count . ' en');
                $attributeValue->setName($attributeValueName);

                $attributeValueSwatch = new AttributeValue\Dto\Swatch();
                $attributeValueSwatch->setType(AttributeValue::SWATCH_TYPE_IMAGE);
                $attributeValueSwatch->setValue('https://www.google.de/image');
                $attributeValue->setSwatch($attributeValueSwatch);

                $attribute->setValues([$attributeValue]);
            }

            if ($removeOnTearDown) {
                $this->cleanUpAttributeCodes[] = 'code_' . $count;
            }

            $result[] = $attribute;
        }

        return $result;
    }

    /**
     * @param Attribute\Create[] $sampleAttributes
     * @param array              $meta
     *
     * @return ResponseInterface
     * @throws RequestException
     * @throws UnknownException
     */
    private function createAttributes(array $sampleAttributes, array $meta = [])
    {
        return $this->sdk->getCatalogService()->addAttributes($sampleAttributes, $meta);
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
        return $this->sdk->getCatalogService()->getAttributes($meta);
    }

    /**
     * @param string $attributeCode
     * @param string $localeCode
     *
     * @return Attribute\Get
     * @throws Exception
     *
     */
    private function getAttribute($attributeCode, $localeCode = '')
    {
        return $this->sdk->getCatalogService()->getAttribute($attributeCode, $localeCode);
    }
}
