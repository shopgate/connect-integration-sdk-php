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

use Shopgate\ConnectSdk\Dto\Base;
use Shopgate\ConnectSdk\Dto\Segmentation\Segment;

abstract class SegmentationUtility extends CustomerUtility
{
    protected function provideSampleSegmentsData()
    {
        return [
            [
                'code' => 'customersDE',
                'name' => 'DE customers',
                'description' => 'This segments contain all DE customers',
                'type' => 'fixed',
                'status' => 'active',
                'rules' => [
                    'contacts.country' => 'DE',
                ],
            ],
            [
                'code' => 'customersUS',
                'name' => 'DE customers',
                'description' => 'This segments contain all DE customers',
                'type' => 'fixed',
                'status' => 'active',
                'rules' => [
                    'contacts.country' => 'DE',
                ],
            ]
        ];
    }

    protected function setUpSegment()
    {
        $segment = $this->provideSampleSegmentsData()[0];
        $this->sdk->getSegmentationService()->addSegments([new Segment\Create($segment)]);

        $this->deleteEntitiesAfterTestRun(
            self::SEGMENTATION_SERVICE,
            self::METHOD_DELETE_SEGMENT,
            [$segment['code']]
        );
        return $segment;
    }

    protected function toArray($subject)
    {
        if ($subject instanceof Base) {
            return $this->toArray($subject->toArray());
        }
        if (is_array($subject)) {
            foreach ($subject as & $item) {
                if ($item instanceof Base) {
                    $item = $this->toArray($item->toArray());
                }
                if ($item instanceof \stdClass) {
                    $item = (array) $item;
                }
            }
        }
        return $subject;
    }
}
