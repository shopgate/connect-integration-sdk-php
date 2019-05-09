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

namespace Shopgate\ConnectSdk\Tests\Unit\Services\Events\Connector\Entities;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog;
use Shopgate\ConnectSdk\Services\Events\Entities\Catalog\Category;
use Shopgate\ConnectSdk\Tests\Unit\Utility\HttpClientMock;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Services\Events\Connector\Entities\Catalog
 */
class CatalogTest extends TestCase
{
    /**
     * @param array  $expected
     * @param string $folder
     *
     * @dataProvider getClassPathProvider
     */
    public function testGetClassPath($expected, $folder)
    {
        $catalog = new Catalog(new HttpClientMock());

        $this->assertEquals($expected, $catalog->getClassPath($folder));
    }

    /**
     * @return array
     */
    public function getClassPathProvider()
    {
        return [
            ['\Catalog\SomeFolder', 'SomeFolder'],
            ['\Catalog', ''],
            ['\Catalog', null],
        ];
    }

    /**
     * @param string $expected   - expected class name
     * @param string $folderName - folder to look into
     * @param bool   $direct     - is the call direct or async
     *
     * @throws ReflectionException
     * @dataProvider getInstantiateClassProvider
     *
     */
    public function testInstantiateClass($expected, $folderName, $direct)
    {
        /** @var MockObject|Catalog $catalog */
        $method  = self::getMethod(Catalog::class, 'instantiateClass');
        $catalog = new Catalog(new HttpClientMock());
        $actual  = $method->invokeArgs($catalog, [$folderName, $direct]);
        /** @noinspection PhpParamsInspection */
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return array
     */
    public function getInstantiateClassProvider()
    {
        return [
            [Category\Async::class, 'Category', false],
            [Category\Direct::class, 'Category', true],
        ];
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected static function getMethod($class, $method)
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $class = new ReflectionClass($class);
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }
}
