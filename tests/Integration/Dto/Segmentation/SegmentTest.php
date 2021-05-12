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

use Shopgate\ConnectSdk\Dto\Segmentation\Segment;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Service\Segmentation;
use Shopgate\ConnectSdk\Tests\Integration\SegmentationUtility;

class SegmentTest extends SegmentationUtility
{
    /**
     * @var Segmentation
     */
    private $segmentationService;

    public function setUp()
    {
        parent::setUp();
        $this->segmentationService = $this->sdk->getSegmentationService();
    }

    public function testAddSegment()
    {
        $segments = $this->provideSampleSegmentsData();
        try {
            $result = $this->segmentationService->addSegments([
                new Segment\Create($segments[0])
            ]);
            $this->assertEquals(201, $result->getStatusCode());
        } catch (RequestException $e) {
            // Exist already
            if ($e->getStatusCode() === 409) {
                $this->testRemoveSegments($segments);
                return $this->testAddSegment();
            }
        }
        return $segments;
    }

    /**
     * @param array $segment
     * @return array
     * @depends testAddSegment
     */
    public function testGetSegment(array $segments)
    {
        $result = $this->segmentationService->getSegment($segments[0]['code']);
        $this->assertInstanceOf(Segment\Get::class, $result);

        $segmentData = $this->toArray($result);
        $this->assertArraySubset($segments[0], $segmentData);
        return $segments;
    }

    /**
     * @param array $segment
     * @return array
     * @depends testGetSegment
     */
    public function testUpdateSegment(array $segments)
    {
        $result = $this->segmentationService->updateSegment(
            $segments[0]['code'],
            new Segment\Update(['name' => 'Customers DE'])
        );
        $this->assertEquals(204, $result->getStatusCode());

        $segments[0]['name'] = 'Customers DE';
        return $segments;
    }

    /**
     * @param array $segments
     * @return array
     * @depends testUpdateSegment
     */
    public function testCloneSegment(array $segments)
    {
        $result = $this->segmentationService->cloneSegment(
            $segments[0]['code'],
            new Segment\CloneSegment(['newSegmentCode' => $segments[1]['code']])
        );
        $this->assertEquals(202, $result->getStatusCode());
        $segments[1]['name'] = $segments[0]['name'];
        return $segments;
    }

    /**
     * @param array $segments
     * @depends testCloneSegment
     * @return array
     */
    public function testGetAllSegments(array $segments)
    {
        $result = $this->segmentationService->getSegments();

        $this->assertArraySubset(['totalItemCount' => 2], $result['meta']->toArray());
        $this->assertArraySubset(
            $segments,
            $this->toArray($result['segments'])
        );

        return $segments;
    }

    /**
     * @param array $segments
     * @depends testGetAllSegments
     */
    public function testRemoveSegments(array $segments)
    {
        foreach ($segments as $segment) {
            try {
                $result = $this->segmentationService->deleteSegment($segment['code']);
                $this->assertEquals(202, $result->getStatusCode());
            } catch (NotFoundException $ignore) {
            }
        }
    }
}
