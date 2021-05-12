<?php

/** @noinspection PhpUnhandledExceptionInspection,PhpDocMissingThrowsInspection */

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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Segmentation;

use Shopgate\ConnectSdk\Dto\Segmentation\Member;
use Shopgate\ConnectSdk\Service\Segmentation;
use Shopgate\ConnectSdk\Tests\Integration\SegmentationUtility;

/**
 * @property \Shopgate\ConnectSdk\Dto\Customer\Customer\Create[] customer
 * @property mixed segment
 */
class MembersTest extends SegmentationUtility
{
    /**
     * @var Segmentation
     */
    private $segmentationService;

    private $member = [
        'id' => '',
        'type' => 'customer'
    ];

    public function setUp()
    {
        parent::setUp();

        $this->customer = $this->provideSampleCustomers(1);
        $response = $this->sdk->getCustomerService()->addCustomers(
            $this->customer
        );
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            $response['ids']
        );
        $this->member['id'] = reset($response['ids']);

        $this->segment = $this->setUpSegment();
    }

    public function testAddMembersByFilter()
    {
        $result = $this->sdk->getSegmentationService()->addMembersByFilter(
            $this->segment['code'],
            ['filter' => ['customer.emailAddress' => ['$ct' => [self::CUSTOMER_EMAIL]]]]
        );
        $this->assertEquals(202, $result->getStatusCode());
    }

    public function testAddGetDeleteMembers()
    {
        // Add members
        $result = $this->sdk->getSegmentationService()->addSegmentMembers(
            $this->segment['code'],
            [new Member\Add($this->member)]
        );
        $this->assertEquals(201, $result->getStatusCode());

        // Get members
        $result = $this->sdk->getSegmentationService()->getSegmentMembers($this->segment['code']);
        $this->assertArraySubset(['totalItemCount' => 1], $result['meta']->toArray());
        $this->assertArraySubset(
            [$this->member],
            $this->toArray($result['members'])
        );

        // Get segments of member
        $result = $this->sdk->getSegmentationService()->getCustomerSegments($this->member['id']);
        $this->assertArraySubset(
            [$this->segment],
            $this->toArray($result['segments'])
        );

        // Delete members
        $result = $this->sdk->getSegmentationService()->deleteSegmentMembers(
            $this->segment['code'],
            [new Member\Delete($this->member)]
        );
        $this->assertEquals(202, $result->getStatusCode());
    }
}
