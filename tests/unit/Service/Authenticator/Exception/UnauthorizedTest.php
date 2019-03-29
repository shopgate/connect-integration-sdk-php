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

namespace unit\Service\Authenticator\Exception;

use Shopgate\CloudIntegrationSdk\Service\Authenticator\Exception\Unauthorized as UnauthorizedException;

class UnauthorizedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param \Exception  $expectedResult
     * @param string|null $errorMessage
     *
     * @covers       UnauthorizedException::__construct()
     * @dataProvider provideConstructCases
     */
    public function test_construct($expectedResult, $errorMessage)
    {
        $result = $errorMessage === null ? new UnauthorizedException() : new UnauthorizedException($errorMessage);
        $this->assertEquals($expectedResult->getMessage(), $result->getMessage());
        $this->assertEquals($expectedResult->getCode(), $result->getCode());
    }

    /**
     * @return array
     */
    public function provideConstructCases()
    {
        return array(
            'no message'    => array(
                new \Exception('Unauthorized: Invalid or missing authorization.', 401),
                null,
            ),
            'empty message' => array(
                new \Exception('Unauthorized: Invalid or missing authorization.', 401),
                '',
            ),
            'some message'  => array(
                new \Exception('Unauthorized: some error', 401),
                'some error',
            ),
        );
    }
}
