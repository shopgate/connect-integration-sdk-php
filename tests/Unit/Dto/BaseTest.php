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

namespace Shopgate\ConnectSdk\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Base;

class BaseTest extends TestCase
{
    /**
     * Dto\Exceptions\InvalidKeyException : The key "test" does not exist in this Dto.
     */
    public function testInvalidKeyException_ValueNotSet()
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
        $base = new Base(null, $schema);

        // Act
        $result = $base->getTest();

        // Assert
        $this->assertNull($result);
    }

    /**
     * Dto\Exceptions\InvalidDataTypeException : The get() method cannot be used on scalar objects.  Use toScalar() instead.
     */
    public function testInvalidDataTypeException_GetValueOnScalar()
    {
        //Arrange
        $base = new Base();

        // Act
        $result = $base->getNonExistentValue();

        // Assert
        $this->assertNull($result);
    }

    /**
     * InvalidArgumentException : Invalid data type for get() method. Scalar required.
     */
    public function testInvalidArgumentException_InvalidDataType()
    {
        //Arrange
        $base = new Base();

        // Act
        $result = $base->get([]);

        // Assert
        $this->assertNull($result);
    }


    /**
     * Dto\Exceptions\InvalidKeyException : Key not allowed by "properties", "patternProperties", or "additionalProperties": test
     */
    public function testInvalidKeyException_AdditionalPropertiesNotAllowed()
    {
        //Arrange
        $schema = [
            'type' => 'object',
            'properties' => [
                'code' => ['type' => 'string'],
                'other' => ['type' => 'string'],
            ],
            'additionalProperties' => false
        ];
        $base = new Base(null, $schema);

        // Act
        $base->setTest('test');
    }

    /**
     * Dto\Exceptions\InvalidDataTypeException : Properties can only be set on objects.
     */
    public function testInvalidKeyException_SetValueOnScalarObject()
    {
        //Arrange
        $base = new Base();

        // Act
        $base->setNonExistent('1234');
    }

    /**
     * Should not throw an exception
     */
    public function testNonExistingMethodCall()
    {
        //Arrange
        $base = new Base();

        // Act
        $base->nonExistentMethod('1234');
    }
}
