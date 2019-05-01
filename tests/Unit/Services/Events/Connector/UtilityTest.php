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

namespace Shopgate\ConnectSdk\Tests\Unit\Services\Events\Connector;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Shopgate\ConnectSdk\Services\Events\Connector\Utility;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Services\Events\Connector\Utility
 */
class UtilityTest extends TestCase
{
    /**
     * @return array
     */
    public static function splitNameProvider()
    {
        return [
            [['update', 'category'], 'updateCategory'],
            [['update', 'category'], 'UpdateCategory'],
            [['update'], 'update'],
            [[], null],
            [[], '']
        ];
    }

    /**
     * @return array
     */
    public static function getClassPathProvider()
    {
        return [
            ['\SomeFolder', 'SomeFolder'],
        ];
    }

    /**
     * @param array  $expected
     * @param string $methodName
     *
     * @throws ReflectionException
     * @dataProvider splitNameProvider
     */
    public function testSplitMethodName($expected, $methodName)
    {
        /** @var MockObject | Utility $mock */
        $mock = $this->getMockForTrait(Utility::class);

        $this->assertEquals($expected, $mock->splitMethodName($methodName));
    }

    /**
     * @param array  $expected
     * @param string $folder
     *
     * @throws ReflectionException
     * @dataProvider getClassPathProvider
     */
    public function testGetClassPath($expected, $folder)
    {
        /** @var MockObject | Utility $mock */
        $mock = $this->getMockForTrait(Utility::class);

        $this->assertEquals($expected, $mock->getClassPath($folder));
    }
}
