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
use Dto\Exceptions\InvalidDataTypeException;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Dto\Location\Location;
use Shopgate\ConnectSdk\Tests\Integration\LocationTest as LocationBaseTest;

class LocationTest extends LocationBaseTest
{
    /**
     * @param int      $limit
     * @param int      $offset
     * @param int      $expectedLocationCount
     * @param string[] $expectedLocationCodes
     *
     * @throws Exception
     *
     * @dataProvider provideLocationLimitCases
     */
    public function testLocationLimit($limit, $offset, $expectedLocationCount, $expectedLocationCodes)
    {
        $this->markTestSkipped('Skipped due to issue with limiting and offsetting location results');
        // Arrange
        $sampleLocations = $this->provideSampleLocations();
        $sampleLocationCodes = $this->getLocationCodes($sampleLocations);
        $this->createLocations($sampleLocations);

        $params = [];
        if (isset($limit)) {
            $params['limit'] = $limit;
        }
        if (isset($offset)) {
            $params['offset'] = $offset;
        }

        // Act
        $locations = $this->getLocations($sampleLocationCodes, $params);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, $sampleLocationCodes);

        // Assert
        /** @noinspection PhpParamsInspection */
        $locationCodes = $this->getLocationCodes($locations->getLocations());

        $this->assertCount($expectedLocationCount, $locations->getLocations());
        $this->assertEquals($expectedLocationCodes, $locationCodes);
        if (isset($limit)) {
            $this->assertEquals($limit, $locations->getMeta()->getLimit());
        }
        if (isset($offset)) {
            $this->assertEquals($offset, $locations->getMeta()->getOffset());
        }
    }

    /**
     * @return array
     */
    public function provideLocationLimitCases()
    {
        return [
            'get the second'    => [
                'limit'                 => 1,
                'offset'                => 1,
                'expectedCount'         => 1,
                'expectedCategoryCodes' => [
                    self::LOCATION_CODE_SECOND
                ]
            ],
            'get the first'     => [
                'limit'         => 1,
                'offset'        => 0,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::LOCATION_CODE
                ]
            ],
            'get two'           => [
                'limit'         => 2,
                'offset'        => 0,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::LOCATION_CODE,
                    self::LOCATION_CODE_SECOND
                ]
            ],
            'limit 1'           => [
                'limit'         => 1,
                'offset'        => null,
                'expectedCount' => 1,
                'expectedCodes' => [
                    self::LOCATION_CODE
                ]
            ],
            'limit 2'           => [
                'limit'         => 2,
                'offset'        => null,
                'expectedCount' => 2,
                'expectedCodes' => [
                    self::LOCATION_CODE,
                    self::LOCATION_CODE_SECOND
                ]
            ],
            'offset 1'          => [
                'limit'         => null,
                'offset'        => 1,
                'expectedCount' => 1,
                'expectedCodes' => [self::LOCATION_CODE_SECOND]
            ],
            'offset 2'          => [
                'limit'         => null,
                'offset'        => 2,
                'expectedCount' => 0,
                'expectedCodes' => []
            ],
            'no entities found' => [
                'limit'         => 1,
                'offset'        => 2,
                'expectedCount' => 0,
                'expectedCodes' => []
            ]
        ];
    }

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
    public function testCreateLocationEvent()
    {
        $this->markTestSkipped('Skipped because there is something wrong with event location creation');
        // Arrange
        $sampleLocations = $this->provideSampleLocations();
        $sampleLocationCodes = $this->getLocationCodes($sampleLocations);

        // Act
        $this->createLocations(
            $sampleLocations,
            ['requestType' => 'event']
        );
        sleep(self::SLEEP_TIME_AFTER_EVENT);
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
        $this->assertEquals(asort($expectedLocationCodes), asort($locationCodes));
    }

    /**
     * @param array $original
     * @param array $updated
     *
     * @throws Exception
     *
     * @dataProvider updateLocationDataProvider
     */
    public function testUpdateLocation(array $original, array $updated)
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
        $requestFields = array_keys(array_merge($original, $updated));
        $locations = $this->getLocations([$locationCode], ['fields' => implode(',', $requestFields)]);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, [$locationCode]);

        // Assert
        // check values that should have changed
        foreach ($updated as $index => $value) {
            $this->doComplexComparisons($value, $locations->getLocations()[0]->get($index));
        }

        // check values that should not have changed
        foreach (array_diff_key($original, $updated) as $index => $value) {
            $this->doComplexComparisons($value, $locations->getLocations()[0]->get($index));
        }
    }

    /**
     * @param mixed $expectedValue
     * @param mixed $testValue
     */
    public function doComplexComparisons($expectedValue, $testValue)
    {
        if (is_numeric($expectedValue)) {
            $this->assertTrue(is_numeric($testValue));
            $this->assertEquals(floor($expectedValue), floor($testValue));
            return;
        }

        if (is_array($expectedValue)) {
            $expectedValue = array_map([$this, 'convertDtoToArrayIfPossible'], $expectedValue);
        }

        if (is_array($testValue)) {
            $testValue = array_map([$this, 'convertDtoToArrayIfPossible'], $testValue);
        }

        $expectedValue = $this->convertDtoToArrayIfPossible($expectedValue);
        $testValue = $this->convertDtoToArrayIfPossible($testValue);

        $this->assertEquals($expectedValue, $testValue);
    }

    /**
     * @return array
     */
    public function updateLocationDataProvider()
    {
        return [
            'change name but not locale code'    => [
                'original' => [
                    'name'       => 'original location name',
                    'localeCode' => 'en-US',
                ],
                'update'   => [
                    'name' => 'new location name'
                ]
            ],
            'multiple simple value update'       => [
                'original' => [
                    'name'       => 'original location name',
                    'latitude'   => 41.8781,
                    'longitude'  => 87.6298,
                    'localeCode' => 'en-US',
                    'timeZone'   => 'America/Chicago',
                    'isDefault'  => true


                ],
                'update'   => [
                    'name'       => 'new location name',
                    'latitude'   => 26.3054,
                    'longitude'  => 31.1367,
                    'localeCode' => 'en-ZA',
                    'timeZone'   => 'Africa/Mbabane',
                    'isDefault'  => false
                ]
            ],
            'change detail'                      => [
                'original' => [
                    'details' => new Location\Dto\Details(
                        ['manager' => 'John Doe', 'image' => 'https://great-image.com/some-image.jpg', 'departments' => ['marketing'], 'services' => ['gift wrapping']]
                    )
                ],
                'update'   => [
                    'details' => new Location\Dto\Details(
                        ['manager' => 'Jane Smith', 'image' => 'https://great-image.com/some-new-image.jpg', 'departments' => ['marketing'], 'services' => ['gift wrapping', 'installation']]
                    )

                ]
            ],
            'change address'                     => [
                'original' => [
                    'addresses' => [
                        new Location\Dto\Address(
                            ['code' => 'address-1', 'name' => 'address one', 'street' => '123 street', 'postalCode' => '78732', 'city' => 'Austin', 'country' => 'US', 'isPrimary' => false]
                        )
                    ]
                ],
                'update'   => [
                    'addresses' => [
                        new Location\Dto\Address(
                            ['code' => 'address-1', 'name' => 'address one the best', 'street' => '123 street', 'postalCode' => '78732', 'city' => 'Austin', 'country' => 'US', 'isPrimary' => true]
                        ),
                        new Location\Dto\Address(['code' => 'address-2', 'name' => 'address two', 'street' => '321 street', 'postalCode' => '78732', 'city' => 'Austin', 'country' => 'US'])
                    ]
                ]
            ],
            'add inventory'                      => [
                'original' => [],
                'update'   => [
                    'inventory' => new Location\Dto\Inventory(['isManaged' => true, 'mode' => 'blind', 'safetyStockMode' => 'disabled', 'safetyStockType' => 'count'])
                ]
            ],
            'change inventory'                   => [
                'original' => [
                    'inventory' => new Location\Dto\Inventory(['isManaged' => true, 'mode' => 'blind', 'safetyStockMode' => 'disabled', 'safetyStockType' => 'count'])
                ],
                'update'   => [
                    'inventory' => new Location\Dto\Inventory(['isManaged' => true, 'mode' => 'integrated', 'safetyStockMode' => 'enabled', 'safetyStock' => 10, 'safetyStockType' => 'percentage'])
                ]
            ],
            'add settings'                       => [
                'original' => [],
                'update'   => [
                    'settings' => new Location\Dto\Settings(
                        ['enableInStorePickup' => true, 'enableShipFromStore' => false, 'enableInLocationFinder' => false, 'enableInventoryBrowse' => false, 'enableForRelate' => false,
                         'showStoreHours'      => false]
                    )
                ]
            ],
            'change settings'                    => [
                'original' => [
                    'settings' => new Location\Dto\Settings(
                        ['enableInStorePickup' => true, 'enableShipFromStore' => false, 'enableInLocationFinder' => false, 'enableInventoryBrowse' => false, 'enableForRelate' => false,
                         'showStoreHours'      => false]
                    )
                ],
                'update'   => [
                    'settings' => new Location\Dto\Settings(
                        ['enableInStorePickup' => true, 'enableShipFromStore' => true, 'enableInLocationFinder' => false, 'enableInventoryBrowse' => true, 'enableForRelate' => false,
                         'showStoreHours'      => false]
                    )
                ]
            ],
            'add operationHours'                 => [
                'original' => [],
                'update'   => [
                    'operationHours' => new Location\Dto\OperationHours(['mon' => '8a - 9p', 'tue' => '8a - 9p', 'wed' => '8a - 9p', 'thu' => '8a - 9p', 'fri' => '8a - 12p'])
                ]
            ],
            'change operationHours'              => [
                'original' => [
                    'operationHours' => new Location\Dto\OperationHours(['sun' => '12p - 4p', 'mon' => '8a - 9p', 'tue' => '8a - 9p', 'wed' => '8a - 9p', 'thu' => '8a - 9p', 'fri' => '8a - 12p'])
                ],
                'update'   => [
                    'operationHours' => new Location\Dto\OperationHours(
                        ['sun' => null, 'mon' => '8a - 9p', 'tue' => '8a - 9p', 'wed' => '8a - 9p', 'thu' => '8a - 9p', 'fri' => '8a - 9p', 'sat' => '8a - 1p']
                    )
                ]
            ],
            'add supportedFulfillmentMethods'    => [
                'original' => [],
                'update'   => [
                    'supportedFulfillmentMethods' => ['directShip']
                ]
            ],
            'change supportedFulfillmentMethods' => [
                'original' => [
                    'supportedFulfillmentMethods' => ['directShip']
                ],
                'update'   => [
                    'supportedFulfillmentMethods' => ['directShip', 'pickUpInStore']
                ]
            ],
            'change detail but not inventory'    => [
                'original' => [
                    'inventory' => new Location\Dto\Inventory(['isManaged' => true, 'mode' => 'blind', 'safetyStockMode' => 'disabled', 'safetyStockType' => 'count']),
                    'details'   => new Location\Dto\Details(
                        ['manager' => 'John Doe', 'image' => 'https://great-image.com/some-image.jpg', 'departments' => ['marketing'], 'services' => ['gift wrapping']]
                    )
                ],
                'updated'  => [
                    'details' => new Location\Dto\Details(
                        ['manager' => 'Joe Shmoe', 'image' => 'https://great-image.com/some-image.jpg', 'departments' => ['marketing', 'Purchasing'], 'services' => ['gift wrapping']]
                    )
                ]
            ]
        ];
    }

    /**
     * @param array     $locationData
     * @param Exception $expectedException
     *
     * @dataProvider providedCreateLocationWithMissingRequiredFields
     */
    public function testCreateLocationsWithMissingRequiredFields(array $locationData, Exception $expectedException)
    {
        // Arrange
        $location = new Location\Create($locationData);

        // Act
        try {
            $this->createLocations([$location]);
        } catch (Exception $exception) {
            // Assert
            $this->assertInstanceOf(get_class($expectedException), $exception);
            $this->assertEquals($expectedException->getStatusCode(), $exception->getStatusCode());

            return;
        }

        $this->fail('Expected ' . get_class($expectedException) . ' but was not thrown');
        // Clean Up
        if (!empty($location['code'])) {
            $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, [$location['code']]);
        }
    }

    public function providedCreateLocationWithMissingRequiredFields()
    {
        return [
            'missing name' => [
                'locationData'      => [
                    'code' => 'test-code',
                    'type' => new Location\Dto\Type(['code' => Location::TYPE_WAREHOUSE, 'name' => Location::TYPE_WAREHOUSE])
                ],
                'expectedException' => new RequestException(400)
            ],
            'missing code' => [
                'locationData'      => [
                    'name' => 'test name',
                    'type' => new Location\Dto\Type(['code' => Location::TYPE_WAREHOUSE, 'name' => Location::TYPE_WAREHOUSE])
                ],
                'expectedException' => new RequestException(400)
            ],
            'missing type' => [
                'locationData'      => [
                    'name' => 'test name',
                    'code' => 'test-code'
                ],
                'expectedException' => new RequestException(400)
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function testUpdateLocationWithoutAnyDataGiven()
    {
        // Arrange
        $sampleLocation = $this->provideSampleCreateLocation(self::LOCATION_CODE, 'Location name one', Location::TYPE_WAREHOUSE);
        $this->createLocations([$sampleLocation]);
        $updateLocation = new Location\Update();

        // Act
        $response = $this->sdk->getLocationService()->updateLocation(self::LOCATION_CODE, $updateLocation);

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::LOCATION_SERVICE, self::METHOD_DELETE_LOCATION, [self::LOCATION_CODE]);

        // Assert
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testUpdateToNonexistentLocation()
    {
        // Arrange
        $updateLocation = new Location\Update(['name' => 'new name']);

        // Assert
        $this->expectException(NotFoundException::class);

        // Act
        $this->sdk->getLocationService()->updateLocation('nonexistent', $updateLocation);
    }

    /**
     * @param array $locationCodes
     * @param array $meta
     *
     * @return Location\GetList
     * @throws Exception
     *
     */
    private function getLocations(array $locationCodes = [], array $meta = [])
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

    /**
     * @param mixed $value
     * @return mixed
     */
    public function convertDtoToArrayIfPossible($value)
    {
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $this->recursivelyRemoveNullFromArray($value->toArray());
        }
        return $value;
    }

    /**
     * @param array $array
     * @return array
     */
    public function recursivelyRemoveNullFromArray(array $array)
    {
        $array = array_filter($array);
        return array_map(
            function ($elem) {
                if (is_array($elem)) {
                    return $this->recursivelyRemoveNullFromArray($elem);
                }
                return $elem;
            },
            $array
        );
    }
}
