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

use Shopgate\ConnectSdk\Dto\Location\Location;

abstract class LocationTest extends ShopgateSdkTest
{
    const LOCATION_SERVICE       = 'omni-location';
    const METHOD_DELETE_LOCATION = 'deleteLocation';
    const LOCATION_CODE          = 'integration-test';
    const LOCATION_CODE_SECOND   = 'integration-test-2';
    const LOCATION_CODE_THIRD    = 'integration-test-3';
    const LOCATION_CODE_FOURTH    = 'integration-test-4';

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::LOCATION_SERVICE,
            $this->sdk->getLocationService(),
            [
                self::METHOD_DELETE_LOCATION  => []
            ]
        );
    }

    /**
     * @return Location\Create[]
     */
    protected function provideSampleLocations()
    {
        return [
            $this->provideSampleCreateLocation(self::LOCATION_CODE, 'Integration Test Location Store', Location::TYPE_STORE),
            $this->provideSampleCreateLocation(self::LOCATION_CODE_SECOND, 'Integration Test Location Warehouse', Location::TYPE_WAREHOUSE),
            $this->provideSampleCreateLocation(self::LOCATION_CODE_THIRD, 'Integration Test Location Drop Shipping', Location::TYPE_DROP_SHIPPING),
            $this->provideSampleCreateLocation(self::LOCATION_CODE_FOURTH, 'Integration Test Location 3rd Party Fulfillment', Location::TYPE_3RD_PARTY_FULFILLMENT),
        ];
    }

    /**
     * @param string $code
     * @param string $name
     * @param string $type
     *
     * @return Location\Create
     */
    protected function provideSampleCreateLocation(
        $code,
        $name,
        $type
    ) {
        $location = new Location\Create();
        $typeObj = new Location\Dto\Type(['code' => $type]);
        $location->setCode($code)
            ->setName($name)
            ->setType($typeObj);

        return $location;
    }

    /**
     * @param Location\Create[] $locations
     *
     * @return string[]
     */
    protected function getLocationCodes($locations)
    {
        $locationCodes = [];
        foreach ($locations as $location) {
            $locationCodes[] = $location->get('code');
        }

        return $locationCodes;
    }
}
