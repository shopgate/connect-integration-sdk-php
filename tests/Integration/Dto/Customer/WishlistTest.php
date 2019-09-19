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
 * @author    Shopgate Inc, 804 Congress Ave, Austin, Texas 78701
 *            <interfaces@shopgate.com>
 * @copyright Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License,
 *            Version 2.0
 */

namespace Shopgate\ConnectSdk\Tests\Integration\Dto\Customer;

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CustomerTest as CustomerIntegrationTest;
use Shopgate\ConnectSdk\Dto\Customer\Customer;
use Shopgate\ConnectSdk\Dto\Customer\Wishlist;
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Exception\RequestException;

class WishlistTest extends CustomerIntegrationTest
{
    /**
     * @param array $sampleWishlists
     *
     * @dataProvider providerCreateWishlist
     *
     * @throws Exception
     */
    public function testCreateWishlist($sampleWishlists)
    {
        // Arrange
        $customerId = $this->createCustomer();
        $productCodes = [];
        foreach ($sampleWishlists as $sampleWishlist) {
            if (empty($sampleWishlist['items'])) {
                continue;
            }
            $productCodes = array_merge(
                $productCodes,
                array_map(
                    [$this, 'getItemProductCode'], $sampleWishlist['items']
                )
            );
        }

        $this->addSampleProducts($productCodes);

        // Act
        $this->sdk->getCustomerService()->addWishlists(
            $customerId, array_map(
                function ($sampleWishlist) {
                    return new Wishlist\Create($sampleWishlist);
                }, $sampleWishlists
            )
        );

        // Assert
        $wishlistGetList = $this->sdk->getCustomerService()->getWishlists(
            $customerId
        );

        // CleanUp
        $this->cleanupWishlists(
            $this->getWishlistsDeleteIdsForCleanup(
                $wishlistGetList, $customerId
            ),
            $customerId,
            $productCodes
        );

        // Assert
        $wishlists = [];
        // must call each individual wishlist to get items
        foreach ($sampleWishlists as $sampleWishlist) {
            $wishlists[] = $this->sdk->getCustomerService()->getWishlist(
                $sampleWishlist['code'], $customerId
            );
        }
        $this->assertCount(
            count($sampleWishlists), $wishlistGetList->getWishlists()
        );
        foreach ($sampleWishlists as $sampleWishlist) {
            $actualWishlist = $this->findWishListByCode(
                $sampleWishlist['code'], $wishlists
            );
            $sampleWishlistAsGet = new Wishlist\Get($sampleWishlist);
            // must evaluate piece by piece
            foreach ($sampleWishlist as $key => $parts) {
                $this->assertEquals(
                    $sampleWishlistAsGet->get($key), $actualWishlist->get($key)
                );
            }
        }
    }

    /**
     * @return array
     */
    public function providerCreateWishlist()
    {
        return [
            'create one wishlist' => [
                'sampleWishlists' => [
                    [
                        'code' => 'wishlist-one',
                        'name' => 'Wishlist One'
                    ]
                ]
            ],
            'create two wishlists' => [
                'sampleWishlists' => [

                    [
                        'code' => 'wishlist-a',
                        'name' => 'Wishlist A'
                    ],
                    [
                        'code' => 'wishlist-b',
                        'name' => 'Wishlist B'
                    ],
                ]
            ],
            'create one wishlist with items' => [
                'sampleWishlists' => [
                    [
                        'code' => 'wishlist-1',
                        'name' => 'wishlist 1',
                        'items' => [
                            ['productCode' => 'product-1']
                        ]
                    ]
                ]
            ],
            'create two wishlists with items' => [
                'sampleWishlists' => [
                    [
                        'code' => 'wishlist-uno',
                        'name' => 'wishlist Uno',
                        'items' => [
                            ['productCode' => 'product-a'],
                            ['productCode' => 'product-b']
                        ]
                    ],
                    [
                        'code' => 'wishlist-dos',
                        'name' => 'wishlist Dos',
                        'items' => [
                            ['productCode' => 'product-8'],
                            ['productCode' => 'product-9']
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @depends testCreateWishlist
     *
     * @throws Exception
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
        $this->sdk->getCustomerService()->addWishlists(
            $customerId, [$sampleWishlist]
        );

        // Act
        $wishlist = $this->sdk->getCustomerService()->getWishlist(
            self::WISHLIST_CODE, $customerId
        );

        // CleanUp
        $this->cleanupWishlists(
            [[self::WISHLIST_CODE, $customerId]],
            $customerId
        );

        // Assert
        $this->assertEquals(self::WISHLIST_CODE, $wishlist->getCode());
        $this->assertEquals($wishlistName, $wishlist->getName());
    }

    /**
     * @param array $original
     * @param array $updated
     *
     * @depends      testCreateWishlist
     *
     * @throws Exception
     *
     * @dataProvider providerUpdateWishlist
     */
    public function testUpdateWishlist($original, $updated)
    {
        // Arrange
        $defaultFields = ['code' => self::WISHLIST_CODE];
        $originalCreateFields = array_merge($defaultFields, $original);
        $customerId = $this->createCustomer();
        $sampleWishlist = new Wishlist\Create(
            $originalCreateFields
        );
        $updatedCode = !empty($updated['code']) ? $updated['code']
            : $originalCreateFields['code'];

        $originalItems = !empty($original['items']) ? $original['items'] : [];
        $updatedItems = !empty($updated['items']) ? $updated['items'] : [];
        $productCodes = array_merge(
            array_map([$this, 'getItemProductCode'], $originalItems),
            array_map([$this, 'getItemProductCode'], $updatedItems)
        );
        $this->addSampleProducts($productCodes);
        $this->sdk->getCustomerService()->addWishlists(
            $customerId, [$sampleWishlist]
        );
        $updateWishlist = new Wishlist\Update($updated);

        // Act
        $this->sdk->getCustomerService()->updateWishlist(
            $originalCreateFields['code'], $customerId, $updateWishlist
        );

        // Assert
        $wishlistAfterUpdate = $this->sdk->getCustomerService()->getWishlist(
            $updatedCode, $customerId
        );

        // CleanUp
        $this->cleanupWishlists(
            [[$wishlistAfterUpdate->getCode(), $customerId]],
            $customerId,
            $productCodes
        );

        // Assert
        $expectedGet = new Wishlist\Get($updated);
        foreach ($updated as $key => $value) {
            $this->assertEquals(
                $expectedGet->get($key), $wishlistAfterUpdate->get($key)
            );
        }
    }

    /**
     * @return array
     */
    public function providerUpdateWishlist()
    {
        return [
            'update code' => [
                'original' => [
                    'code' => 'original-code',
                ],
                'updated' => [
                    'code' => 'updated-code'
                ]
            ],
            'update name' => [
                'original' => [
                    'name' => 'wishlist one',
                ],
                'updated' => [
                    'name' => 'wishlist wedding one'
                ]
            ],
            'update items by adding some' => [
                'original' => [
                    'name' => 'wishlist one',
                ],
                'updated' => [
                    'items' => [
                        ['productCode' => 'product-1'],
                        ['productCode' => 'product-2']
                    ]
                ]
            ],
            'update items by changing them' => [
                'original' => [
                    'name' => 'wishlist one',
                    'items' => [
                        ['productCode' => 'product-1'],
                        ['productCode' => 'product-2']
                    ]
                ],
                'updated' => [
                    'items' => [
                        ['productCode' => 'product-a'],
                        ['productCode' => 'product-b']
                    ]
                ]
            ]
        ];
    }

    /**
     * @depends testCreateWishlist
     *
     * @throws Exception
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
        $this->sdk->getCustomerService()->addWishlists(
            $customerId, $sampleWishlists
        );

        // Assert
        $wishlistsBeforeDelete = $this->sdk->getCustomerService()->getWishlists(
            $customerId
        );

        // Act
        $this->sdk->getCustomerService()->deleteWishlist(
            self::WISHLIST_CODE, $customerId
        );

        // Assert
        $wishlistsAfterDelete = $this->sdk->getCustomerService()->getWishlists(
            $customerId
        );

        // CleanUp
        $this->cleanupWishlists(
            $this->getWishlistsDeleteIdsForCleanup(
                $wishlistsAfterDelete, $customerId
            ),
            $customerId
        );

        // Assert
        $this->assertCount(
            count($wishlistsBeforeDelete->getWishlists()) - 1,
            $wishlistsAfterDelete->getWishlists()
        );
    }

    /**
     * @param string[] $productCodes
     *
     * @depends      testCreateWishlist
     *
     * @dataProvider providerAddWishlistItems
     *
     * @throws Exception
     */
    public function testAddWishlistItems($productCodes)
    {
        // Arrange
        $customerId = $this->createCustomer();
        $sampleWishlist = new Wishlist\Create(
            [
                'code' => self::WISHLIST_CODE,
                'name' => 'Wishlist Name One'
            ]
        );
        $this->addSampleProducts($productCodes);

        $this->sdk->getCustomerService()->addWishlists(
            $customerId, [$sampleWishlist]
        );
        $sampleItems = $this->createWishlistItems($productCodes);

        // Act
        $this->sdk->getCustomerService()->addWishlistItems(
            $customerId, self::WISHLIST_CODE, $sampleItems
        );

        // Assert
        $wishlist = $this->sdk->getCustomerService()
            ->getWishlist(
                self::WISHLIST_CODE, $customerId
            );
        $returnedWishlistProductCodes = $this->getWishlistItemsProductCodes(
            $wishlist
        );

        // CleanUp
        $this->cleanupWishlists(
            [[self::WISHLIST_CODE, $customerId]],
            $customerId,
            $productCodes
        );

        // Assert
        $this->assertEquals($productCodes, $returnedWishlistProductCodes);
    }

    public function providerAddWishlistItems()
    {
        return [
            'add one item' => [
                'productCodes' => [self::WISHLIST_PRODUCT_CODE]
            ],
            'add two items' => [
                'productCodes' => ['wishlist-item-1', 'wishlist-item-2']
            ]
        ];
    }

    /**
     * @depends testCreateWishlist
     *
     * @throws Exception
     */
    public function testDeleteWishlistItems()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $productCodes = [
            self::WISHLIST_PRODUCT_CODE,
            self::WISHLIST_PRODUCT_CODE_TWO
        ];
        $this->addSampleProducts($productCodes);
        $sampleWishlist = new Wishlist\Create(
            [
                'code' => self::WISHLIST_CODE,
                'name' => 'Wishlist Name One',
                'items' => $this->createWishlistItems($productCodes)
            ]
        );
        $this->sdk->getCustomerService()->addWishlists(
            $customerId, [$sampleWishlist]
        );

        // Act
        $this->sdk->getCustomerService()->deleteWishlistItem(
            self::WISHLIST_PRODUCT_CODE, self::WISHLIST_CODE, $customerId
        );

        // CleanUp
        $this->cleanupWishlists(
            [[self::WISHLIST_CODE, $customerId]],
            $customerId,
            $productCodes
        );

        // Assert
        $wishlist = $this->sdk->getCustomerService()
            ->getWishlist(
                self::WISHLIST_CODE, $customerId
            );
        $returnedWishlistProductCodes = $this->getWishlistItemsProductCodes(
            $wishlist
        );

        $this->assertEquals(
            [self::WISHLIST_PRODUCT_CODE_TWO], $returnedWishlistProductCodes
        );
    }

    /**
     * @depends testCreateWishlist
     *
     * @throws Exception
     */
    public function testCreateWishlistItemWithoutRequiredField()
    {
        // Arrange
        $customerId = $this->createCustomer();
        $sampleWishlist = new Wishlist\Create(
            [
                'code' => self::WISHLIST_CODE,
                'name' => 'Wishlist Name One'
            ]
        );
        $this->sdk->getCustomerService()->addWishlists(
            $customerId, [$sampleWishlist]
        );

        // CleanUp
        $this->cleanupWishlists(
            [[self::WISHLIST_CODE, $customerId]],
            $customerId
        );

        // Act
        $sampleItem = new Wishlist\Dto\Item\Create();
        try {
            $this->sdk->getCustomerService()->addWishlistItems(
                $customerId, self::WISHLIST_CODE, [$sampleItem]
            );
        } catch (Exception $exception) {

            // Assert
            $this->assertInstanceOf(RequestException::class, $exception);

            return;
        }

        // Assert
        $this->fail('Expected ' . RequestException::class . ' but wasn\'t thrown');
    }

    /**
     * @return string customer id
     *
     * @throws Exception
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

    private function getWishlistsDeleteIdsForCleanup(
        Wishlist\GetList $wishlists,
        $customerId
    ) {
        $deleteIds = [];
        foreach ($wishlists->getWishlists() as $wishlist) {
            $deleteIds[] = [$wishlist->getCode(), $customerId];
        }

        return $deleteIds;
    }

    /**
     * @param string[] $deleteIds
     * @param string   $customerId
     * @param string[] $productDeleteIds
     */
    private function cleanupWishlists(
        $deleteIds,
        $customerId,
        $productDeleteIds = []
    ) {
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

        if (!empty($productDeleteIds)) {
            $this->deleteEntitiesAfterTestRun(
                self::CATALOG_SERVICE,
                self::METHOD_DELETE_PRODUCT,
                $productDeleteIds
            );
        }
    }

    /**
     * @param string[] $productIds
     *
     * @throws Exception
     */
    private function addSampleProducts($productIds)
    {
        if (empty($productIds)) {
            return;
        }
        $this->sdk->getCatalogService()->addProducts(
            $this->createSampleProducts($productIds),
            ['requestType' => 'direct']
        );
    }

    /**
     * @param string[] $productIds
     *
     * @return Product\Create[]
     */
    private function createSampleProducts($productIds)
    {
        return array_map([$this, 'createSampleProduct'], $productIds);
    }

    /**
     * @param string $productId
     *
     * @return Product\Create
     *
     * @throws Exception
     */
    private function createSampleProduct($productId)
    {
        $sampleProduct = new Product\Create();
        $sampleProduct->setCode($productId)
            ->setCatalogCode('my_catalog')
            ->setName(new Product\Dto\Name(['en-us' => 'Test Product']))
            ->setStatus(Product\Create::STATUS_ACTIVE)
            ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
            ->setIsInventoryManaged(true)
            ->setPrice(
                new Product\Dto\Price(
                    [
                        'price' => 90,
                        'currencyCode' => Product\Dto\Price::CURRENCY_CODE_EUR
                    ]
                )
            );

        return $sampleProduct;
    }

    /**
     * @param Wishlist\Get $wishlist
     *
     * @return array
     */
    private function getWishlistItemsProductCodes($wishlist)
    {
        $productCodes = [];
        if (empty($wishlist->getItems())) {
            return $productCodes;
        }

        foreach ($wishlist->getItems() as $item) {
            $productCodes[] = $item->getProductCode();
        }

        return $productCodes;
    }

    /**
     * @param string $productCode
     *
     * @return Wishlist\Dto\Item\Create
     *
     * @throws Exception
     */
    private function createWishlistItem($productCode)
    {
        return new Wishlist\Dto\Item\Create(['productCode' => $productCode]);
    }

    /**
     * @param string[] $productCodes
     *
     * @return Wishlist\Dto\Item\Create[] array
     */
    private function createWishlistItems($productCodes)
    {
        return array_map([$this, 'createWishlistItem'], $productCodes);
    }

    /**
     * @param                $code
     * @param Wishlist\Get[] $wishlists
     *
     * @return Wishlist\Get|null
     */
    private function findWishListByCode($code, $wishlists)
    {
        foreach ($wishlists as $wishlist) {
            if ($code == $wishlist->getCode()) {
                return $wishlist;
            }
        }

        return null;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private function getItemProductCode($item)
    {
        return $item['productCode'];
    }
}
