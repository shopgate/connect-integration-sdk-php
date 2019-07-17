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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Location;

use Dto\Dto;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Dto\Location\Location;
use Shopgate\ConnectSdk\Tests\Integration\LocationTest as LocationBaseTest;

class LocationTest extends LocationBaseTest
{
    /**
     * @throws Exception
     */
    public function testCreateLocation()
    {
        // Arrange
        $sampleLocations = $this->provideSampleLocations();
        $sampleLocationCodes = $this->getLocationCodes($sampleLocations);

        // Act
        $this->createLocations($sampleLocations);
        $locations = $this->getLocations($sampleLocationCodes);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, $sampleLocationCodes);

        //Assert
        $this->assertCount(count($sampleLocationCodes), $locations->getLocations());
    }
    /**
     * @throws Exception
     */
    public function testDeleteLocation()
    {
        // Arrange
        $sampleLocations = $this->provideSampleLocations();
        $sampleLocationCodes = $this->getLocationCodes($sampleLocations);
        $this->createLocations($sampleLocations);

        // Act
        $expectedLocationCodes = $sampleLocationCodes;
        array_shift($expectedLocationCodes);
        $this->sdk->getLocationService()->deleteLocation($sampleLocationCodes[0]);
        $locations = $this->getLocations($sampleLocationCodes);


        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, $expectedLocationCodes);

        // Assert

        /** @noinspection PhpParamsInspection */
        $locationCodes = $this->getLocationCodes($locations->getLocations());
        $this->assertEquals($expectedLocationCodes, $locationCodes);

    }
    /**
     * @param array      $original
     * @param array      $updated
     *
     * @throws Exception
     *
     * @dataProvider updateLocationDataProvider
     */
    public function testUpdateLocation($original, $updated)
    {
        // Arrange
        $requiredLocationFields = [
            'code' => self::LOCATION_CODE,
            'name' => 'default name',
            'type' => new Location\Dto\Type(['code' => Location::TYPE_WAREHOUSE])
        ];
        $originalLocation = new Location\Create(array_merge($requiredLocationFields, $original));
        $this->createLocations([$originalLocation]);
        $locationCode = $originalLocation->getCode();

        // Act
        $updateLocation = new Location\Update($updated);
        $this->sdk->getLocationService()->updateLocation($locationCode, $updateLocation);
        $locations = $this->getLocations([$locationCode]);

        // cleanUp
        $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, [$locationCode]);

        // Assert
        foreach ($updated as $index => $value) {
            if (is_numeric($value)) {
                $expected = $locations->getLocations()[0]->get($index);
                $this->assertTrue(is_numeric($expected));
                $this->assertEquals(floor($value), floor($expected));
                continue;
            }
            $this->assertEquals($value, $locations->getLocations()[0]->get($index));
        }


    }

    /**
     * @return array
     */
    public function updateLocationDataProvider()
    {
        return [
            'name change' => [
                'original' => [
                    'name' => 'original location name'
                ],
                'update' => [
                    'name' => 'new location name'
                ]
            ],
            'multiple simple value update' => [
                'original' => [
                    'name' => 'original location name',
                    'latitude' => 41.8781,
                    'longitude' => 87.6298,
                    'localeCode' => 'en-US',
                    'timeZone' => 'America/Chicago',
                    'isDefault' => true


                ],
                'update' => [
                    'name' => 'new location name',
                    'latitude' => 26.3054,
                    'longitude' => 31.1367,
                    'localeCode' => 'en-ZA',
                    'timeZone' => 'Africa/Mbabane',
                    'isDefault' => false
                ]
            ]
        ];
    }

    /**
     * @param array $locationCodes
     * @param array $meta
     *
     * @return Location\GetList
     * @throws Exception
     *
     */
    private function getLocations($locationCodes = [], $meta = [])
    {
        return $this->sdk->getLocationService()->getLocations(
            array_merge(
                ['filters' => ['code' => ['$in' => $locationCodes]]],
                $meta
            )
        );
    }

    /**
     * @param Location\Create[] $sampleLocations
     * @param array             $meta
     *
     * @return ResponseInterface
     * @throws RequestException
     * @throws Exception
     *
     */
    private function createLocations(array $sampleLocations, array $meta = [])
    {
        return $this->sdk->getLocationService()->addLocations($sampleLocations, $meta);
    }
}
