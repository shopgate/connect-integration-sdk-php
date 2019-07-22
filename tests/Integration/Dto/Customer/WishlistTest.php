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

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Customer;

use Shopgate\ConnectSdk\Tests\Integration\CustomerTest;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Customer\Wishlist;
use Shopgate\ConnectSdk\Exception;

class WishlistTest extends CustomerTest
{
    /**
     * @param Wishlist\Create[] $sampleWishlists
     *
     * @dataProvider providerCreateWishlist
     *
     * @throws Exception\Exception
     */
    public function testCreateWishlist($sampleWishlists)
    {
        // Arrange
        $customerId = $this->createCustomer();

        // Act
        $this->sdk->getCustomerService()->addWishlists($customerId, $sampleWishlists);

        // Assert
        $wishlists = $this->sdk->getCustomerService()->getWishlists($customerId);

        $this->assertCount(count($sampleWishlists), $wishlists->getWishlists());

        // CleanUp
        $this->cleanupWishlists(
            $this->getWishlistsDeleteIdsForCleanup($wishlists, $customerId),
            $customerId)
        ;
    }

    /**
     * @return array
     */
    public function providerCreateWishlist()
    {
        return [
            'create one wishlist' => [
                'sampleWishlists' => [
                    new Wishlist\Create([
                        'code' => 'wishlist-one',
                        'name' => 'Wishlist One'
                    ])
                ]
            ],
            'create two wishlists' => [
                'sampleWishlists' => [
                    new Wishlist\Create([
                        'code' => 'wishlist-a',
                        'name' => 'Wishlist A'
                    ]),
                    new Wishlist\Create([
                        'code' => 'wishlist-b',
                        'name' => 'Wishlist B'
                    ]),
                ]
            ]
        ];
    }

    /**
     * @throws Exception\Exception
     */
    public function testGetWishlist()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $wishlistName = 'wishlist test name :)';
        $sampleWishlist = new Wishlist\Create(
            [
                'code' => self::WISHLIST_CODE,
                'name' => $wishlistName
            ]
        );
        $this->sdk->getCustomerService()->addWishlists($customerId, [$sampleWishlist]);

        // Act
        $wishlist = $this->sdk->getCustomerService()->getWishlist(self::WISHLIST_CODE, $customerId);

        // Assert
        $this->assertEquals(self::WISHLIST_CODE, $wishlist->getCode());
        $this->assertEquals($wishlistName, $wishlist->getName());

        // CleanUp
        $this->cleanupWishlists(
            [[self::WISHLIST_CODE, $customerId]],
            $customerId
        );
    }

    /**
     * @throws Exception\Exception
     */
    public function testUpdateWishlist()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $newName = 'new name';
        $sampleWishlist = new Wishlist\Create(
            [
                'code' => self::WISHLIST_CODE,
                'name' => 'original name'
            ]
        );
        $this->sdk->getCustomerService()->addWishlists($customerId, [$sampleWishlist]);

        // Act
        $updateWishlist = new Wishlist\Update(['name' => $newName]);
        $this->sdk->getCustomerService()->updateWishlist(self::WISHLIST_CODE, $customerId, $updateWishlist);

        // Assert
        $wishlistAfterUpdate = $this->sdk->getCustomerService()->getWishlist(self::WISHLIST_CODE, $customerId);
        $this->assertEquals($newName, $wishlistAfterUpdate->getName());

        // CleanUp
        $this->cleanupWishlists(
            [[$wishlistAfterUpdate->getCode(), $customerId]],
            $customerId
        );
    }

    /**
     * @throws Exception\Exception
     */
    public function testWishlistDelete()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $sampleWishlists = [
            new Wishlist\Create(
                [
                    'code' => self::WISHLIST_CODE,
                    'name' => 'Wishlist One'
                ]
            ),
            new Wishlist\Create(
                [
                    'code' => 'wish-list-another-code',
                    'name' => 'Wishlist One'
                ]
            ),

        ];
        $this->sdk->getCustomerService()->addWishlists($customerId, $sampleWishlists);

        // Act
        $wishlistsBeforeDelete = $this->sdk->getCustomerService()->getWishlists($customerId);
        $this->sdk->getCustomerService()->deleteWishlist(self::WISHLIST_CODE, $customerId);
        $wishlistsAfterDelete = $this->sdk->getCustomerService()->getWishlists($customerId);

        // Assert
        $this->assertCount(count($wishlistsBeforeDelete->getWishlists()) - 1, $wishlistsAfterDelete->getWishlists());

        // CleanUp
        $this->cleanupWishlists(
            $this->getWishlistsDeleteIdsForCleanup($wishlistsAfterDelete, $customerId),
            $customerId
        );
    }


    /**
     * @return string customer id
     *
     * @throws Exception\Exception
     */
    private function createCustomer()
    {
        $customer = new Customer\Create();
        $customer->setFirstName('John');
        $customer->setLastName('Doe');
        $customer->setEmailAddress('integration-test@shopgate.com');

        $response = $this->sdk->getCustomerService()->addCustomers([$customer]);

        return array_pop($response['ids']);
    }

    private function getWishlistsDeleteIdsForCleanup(Wishlist\GetList $wishlists, $customerId)
    {
        $deleteIds = [];
        foreach ($wishlists->getWishlists() as $wishlist) {
            $deleteIds[] = [$wishlist->getCode(), $customerId];
        }

        return $deleteIds;
    }

    /**
     * @param array  $deleteIds
     * @param string $customerId
     */
    private function cleanupWishlists($deleteIds, $customerId)
    {
        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_WISHLIST,
            $deleteIds
        );

        $this->deleteEntitiesAfterTestRun(
            self::CUSTOMER_SERVICE,
            self::METHOD_DELETE_CUSTOMER,
            [$customerId]
        );
    }
}
