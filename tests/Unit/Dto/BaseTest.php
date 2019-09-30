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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties\Name as PropertyName;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

class BaseTest extends TestCase
{
    /**
     * Dto\Exceptions\InvalidKeyException : The key "test" does not exist in this Dto.
     *
     * @throws Exception
     */
    public function testInvalidKeyExceptionWithoutValueSet()
    {
        //Arrange
        $schema = [
            'type' => 'object',
            'properties' => [
                'code' => ['type' => 'string'],
                'test' => ['type' => 'string'],
            ],
            'additionalProperties' => true
        ];
        $base = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->setConstructorArgs([null, $schema])
            ->getMockForAbstractClass();

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $base->getTest();

        // Assert
        $this->assertNull($result);
    }

    /**
     * Dto\Exceptions\InvalidDataTypeException : The get() method cannot be used on scalar objects.
     * Use toScalar() instead.
     */
    public function testInvalidDataTypeExceptionWithGetValueOnScalar()
    {
        //Arrange
        $base = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->getMockForAbstractClass();

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $base->getNonExistentValue();

        // Assert
        $this->assertNull($result);
    }

    /**
     * InvalidArgumentException : Invalid data type for get() method. Scalar required.
     */
    public function testInvalidArgumentExceptionWithInvalidDataType()
    {
        //Arrange
        $base = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->getMockForAbstractClass();

        // Act
        $result = $base->get([]);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Dto\Exceptions\InvalidKeyException : Key not allowed by "properties", "patternProperties", or
     * "additionalProperties": test
     *
     * @throws Exception
     *
     * @doesNotPerformAssertions
     */
    public function testInvalidKeyExceptionWithAdditionalPropertiesNotAllowed()
    {
        $this->markTestSkipped(
            'This case is currently irrelevant since all our DTO\'s allow additionalProperties'
        );

        //Arrange
        $schema = [
            'type' => 'object',
            'properties' => [
                'code' => ['type' => 'string'],
                'other' => ['type' => 'string'],
            ],
            'additionalProperties' => false
        ];

        $base = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->setConstructorArgs([null, $schema])
            ->getMockForAbstractClass();

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $base->setTest('test');
    }

    /**
     * Dto\Exceptions\InvalidDataTypeException : Properties can only be set on objects.
     * should throw an exception
     *
     * @doesNotPerformAssertions
     */
    public function testInvalidKeyExceptionWithSetValueOnScalarObject()
    {
        //Arrange
        $base = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->getMockForAbstractClass();

        // Assert
        $this->expectException(InvalidDataTypeException::class);

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $base->setNonExistent('1234');
    }

    /**
     * Dto\Exceptions\InvalidDataTypeException : Properties can only be set on objects.
     * Should throw an exception
     *
     * @doesNotPerformAssertions
     */
    public function testNonExistingMethodCall()
    {
        //Arrange
        $base = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->getMockForAbstractClass();

        // Assert
        $this->expectException(InvalidDataTypeException::class);

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $base->nonExistentMethod('1234');
    }

    /**
     * Should throw an exception when data type is invalid
     *
     * @throws Exception
     */
    public function testShouldThrowExceptionWhenAnyDtoExceptionIsThrown()
    {
        //Arrange
        $schema = [
            'anyOf' => [
                'type' => 'array'
            ]
        ];

        // Assert
        $this->expectException(InvalidDataTypeException::class);

        // Act
        $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->setConstructorArgs([123, $schema])
            ->getMockForAbstractClass();
    }

    /**
     * Should throw an exception when data type is invalid
     *
     * @throws Exception
     */
    public function testShouldThrowExceptionWhenDataTypeIsInvalidClassReference()
    {
        //Arrange
        $schema = [
            'type' => ['$ref' => PropertyName::class],
        ];

        // Assert
        $this->expectException(InvalidDataTypeException::class);

        // Act
        $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->setConstructorArgs([true, $schema])
            ->getMockForAbstractClass();
    }

    /**
     * Should throw an exception when data type is invalid
     *
     * @param string $dataType
     * @param mixed  $data
     *
     * @throws Exception
     *
     * @dataProvider provideInvalidArrayCases
     */
    public function testShouldThrowExceptionWhenDataTypeIsInvalid($dataType, $data)
    {
        //Arrange
        $schema = [
            'type' => 'object',
            'properties' => [
                'testProperty' => ['type' => $dataType, 'strict' => true]
            ]
        ];
        $dto = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->setConstructorArgs([null, $schema])
            ->getMockForAbstractClass();

        // Assert
        $this->expectException(InvalidDataTypeException::class);

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $dto->setTestProperty($data);
    }

    /**
     * @return array
     * @throws InvalidDataTypeException
     */
    public function provideInvalidArrayCases()
    {
        return [
            'array - string' => [
                'array',
                'teststring'
            ],
            'array - object' => [
                'array',
                new Name()
            ],
            'array - int' => [
                'array',
                1
            ],
            'array - float' => [
                'array',
                1.0
            ],
            'string - array' => [
                'string',
                []
            ],
            'string - object' => [
                'string',
                new Name()
            ],
            'string - int' => [
                'string',
                1
            ],
            'string - number' => [
                'string',
                1.0
            ],
            'number - array' => [
                'number',
                []
            ],
            'number - object' => [
                'number',
                new Name()
            ],
            'number - string' => [
                'number',
                'teststring'
            ],
            'integer - array' => [
                'integer',
                []
            ],
            'integer - object' => [
                'integer',
                new Name()
            ],
            'integer - number' => [
                'integer',
                4.5
            ],
            'integer - string' => [
                'integer',
                'teststring'
            ]
        ];
    }

    /**
     * Should not throw an exception when data type is valid
     *
     * @param string $dataType
     * @param mixed  $data
     *
     * @throws Exception
     *
     * @dataProvider provideValidDataTypeTestCases
     */
    public function testShouldNotThrowExceptionWhenDataTypeIsValid($dataType, $data)
    {
        //Arrange
        $schema = [
            'type' => 'object',
            'properties' => [
                'testProperty' => ['type' => $dataType]
            ]
        ];
        $dto = $this
            ->getMockBuilder('Shopgate\ConnectSdk\Dto\Base')
            ->setConstructorArgs([null, $schema])
            ->getMockForAbstractClass();

        // Act
        /** @noinspection PhpUndefinedMethodInspection */
        $dto->setTestProperty($data);
    }

    /**
     * @return array
     *
     * @throws InvalidDataTypeException
     */
    public function provideValidDataTypeTestCases()
    {
        return [
            'array' => [
                'array',
                []
            ],
            'object' => [
                'object',
                new Name()
            ],
            'string' => [
                'string',
                'teststring'
            ],
            'number' => [
                'number',
                5.0
            ],
            'integer' => [
                'integer',
                7
            ],
            'null' => [
                'null',
                null
            ]
        ];
    }
}
