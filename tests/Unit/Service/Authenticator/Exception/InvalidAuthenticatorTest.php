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

namespace Shopgate\CloudIntegrationSdk\Tests\Unit\Service\Authenticator\Exception;

use Shopgate\CloudIntegrationSdk\Service\Authenticator\Exception\InvalidAuthenticator as InvalidAuthenticatorException;

class InvalidAuthenticatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param \Exception $expectedResult
     *
     * @covers       InvalidAuthenticator::__construct()
     * @dataProvider provideConstructCases
     */
    public function testConstruct($expectedResult)
    {
        $result = new InvalidAuthenticatorException();
        $this->assertEquals($expectedResult->getMessage(), $result->getMessage());
        $this->assertEquals($expectedResult->getCode(), $result->getCode());
    }

    /**
     * @return array
     */
    public function provideConstructCases()
    {
        return array(
            'construct' => array(
                new \Exception('Invalid authenticator object.', 1012),
            ),
        );
    }
}
