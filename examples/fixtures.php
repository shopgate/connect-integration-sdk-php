<?php

use Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;
use Shopgate\ConnectSdk\Dto\Catalog\Catalog;
use Shopgate\ConnectSdk\Dto\Location\Location;
use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Inventory;
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Order\Order;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;

const PARENT_CATALOG_CODE = 'BANA';
const CATALOG_CODE = 'NARetail';

const LOCATION_CODE = 'WHS1';

const CATEGORY_CODE = 'Test';
const CATEGORY_CODE_SECOND = 'Second Test';

const PRODUCT_CODE = 'Test';
const PRODUCT_CODE_SECOND = 'Second Test';

const EXTRA_CODE = 'Test';
const EXTRA_CODE_SECOND = 'Second Test';
const EXTRA_VALUE_CODE = 'Test';
const EXTRA_VALUE_CODE_SECOND = 'Seconds Test';

const INVENTORY_SKU = 'Test';
const INVENTORY_SKU_ANOTHER = 'Another Test';
const INVENTORY_SKU_SECOND = 'Second Test';

/**
 * @return array
 *
 * @throws InvalidDataTypeException
 */
function provideParentCatalogs()
{
    $parentCatalogs = [
        (new Shopgate\ConnectSdk\Dto\Catalog\ParentCatalog\Create())
            ->setCode(PARENT_CATALOG_CODE)
            ->setName('Team Banana Parent Catalog')
            ->setIsDefault(true)
            ->setDefaultCurrencyCode('USD')
            ->setDefaultLocaleCode('en-us')
    ];
    return $parentCatalogs;
}

/**
 * @return array
 *
 * @throws InvalidDataTypeException
 */
function provideCatalogs()
{
    $catalogs = [
        'catalogs' => [
            new Catalog\Create([
                'code' => CATALOG_CODE,
                'parentCatalogCode' => PARENT_CATALOG_CODE,
                'name' => 'North American Wholesale',
                'isDefault' => true,
                'defaultLocaleCode' => 'en-us',
                'defaultCurrencyCode' => 'USD',
            ]),
        ]
    ];
    return $catalogs;
}

/**
 * @return Category\Create[]
 *
 * @throws InvalidDataTypeException
 */
function provideSampleCategories()
{
    $categories = [];

    $category = new Category\Create();
    $category->setCode(CATEGORY_CODE)
        ->setName(new Category\Dto\Name(['en-us' => 'Test Category 1']))
        ->setSequenceId(1);
    $category->setDescription(new Category\Dto\Description(['en-us' => 'test description']));
    $category->setUrl(new Category\Dto\Url(['en-us' => 'http://www.example.com']));
    $category->setImage(new Category\Dto\Image(['en-us' => 'http://www.example.com/image.png']));
    $category->setStatus(Category::STATUS_ACTIVE);

    $categories[] = $category;

    $category = new Category\Create();
    $category->setCode(CATEGORY_CODE_SECOND)
        ->setName(new Category\Dto\Name(['en-us' => 'Test Category 2']))
        ->setSequenceId(1);
    $category->setDescription(new Category\Dto\Description(['en-us' => 'test description 2']));
    $category->setUrl(new Category\Dto\Url(['en-us' => 'http://www.example.com/"']));
    $category->setImage(new Category\Dto\Image(['en-us' => 'http://www.example.com/image2.png']));
    $category->setStatus(Category::STATUS_ACTIVE);

    $categories[] = $category;

    return $categories;
}

/**
 * @return Attribute\Create[]
 *
 * @throws InvalidDataTypeException
 */
function provideSampleAttributes()
{
    $attributes = [];

    $extra = new Attribute\Create();
    $extra->setCode(EXTRA_CODE)
        ->setType(Attribute\Create::TYPE_TEXT)
        ->setUse(Attribute\Create::USE_EXTRA)
        ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');
    $extraName = new Name();
    $extraName->add('de-de', 'Extra 1 de');
    $extraName->add('en-us', 'Extra 1 en');
    $extra->setName($extraName);
    $extraValue = new AttributeValue\Create();
    $extraValue->setCode(EXTRA_VALUE_CODE);
    $extraValue->setSequenceId(1);

    $extraValueName = new AttributeValue\Dto\Name();
    $extraValueName->add('de-de', 'Extra 1 Attribute de');
    $extraValueName->add('en-us', 'Extra 1 Attribute en');
    $extraValue->setName($extraValueName);

    $extra->setValues([$extraValue]);

    $attributes[] = $extra;

    $extraSecond = new Attribute\Create;
    $extraSecond->setCode(EXTRA_CODE_SECOND)
        ->setType(Attribute\Create::TYPE_TEXT)
        ->setUse(Attribute\Create::USE_EXTRA)
        ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');
    $extraSecondName = new Name();
    $extraSecondName->add('de-de', 'Extra 2 de');
    $extraSecondName->add('en-us', 'Extra 2 en');
    $extraSecond->setName($extraSecondName);

    $extraSecondValue = new AttributeValue\Create();
    $extraSecondValue->setCode(EXTRA_VALUE_CODE_SECOND);
    $extraSecondValue->setSequenceId(1);
    $extraSecondValueName = new AttributeValue\Dto\Name();
    $extraSecondValueName->add('de-de', 'Extra 2 Attribute de');
    $extraSecondValueName->add('en-us', 'Extra 2 Attribute en');
    $extraSecondValue->setName($extraSecondValueName);

    $extraSecond->setValues([$extraSecondValue]);

    $attributes[] = $extraSecond;

    return $attributes;
}

/**
 * @return Inventory\Create[]
 *
 * @throws InvalidDataTypeException
 */
function provideSampleInventories()
{
    $inventory = new Inventory\Create();
    $inventory->setProductCode(PRODUCT_CODE);
    $inventory->setLocationCode(LOCATION_CODE);
    $inventory->setSku(INVENTORY_SKU);
    $inventory->setOnHand(10);
    $inventory->setBin('test bin');
    $inventory->setBinLocation('DE-DE');
    $inventory->setSafetyStock(1);
    $result[] = $inventory;

    $inventory = new Inventory\Create();
    $inventory->setProductCode(PRODUCT_CODE);
    $inventory->setLocationCode(LOCATION_CODE);
    $inventory->setSku(INVENTORY_SKU_ANOTHER);
    $inventory->setOnHand(15);
    $inventory->setBin('another test bin');
    $inventory->setBinLocation('EN-US');
    $inventory->setSafetyStock(3);
    $result[] = $inventory;

    $inventory = new Inventory\Create();
    $inventory->setProductCode(PRODUCT_CODE_SECOND);
    $inventory->setLocationCode(LOCATION_CODE);
    $inventory->setSku(INVENTORY_SKU_SECOND);
    $inventory->setOnHand(15);
    $inventory->setBin('another test bin');
    $inventory->setBinLocation('EN-US');
    $inventory->setSafetyStock(3);
    $result[] = $inventory;

    return $result;
}

/**
 * @return Inventory\Delete[]
 */
function provideSampleDeleteInventories()
{
    $inventories = [];

    $inventory = new Inventory\Delete();
    $inventory->setProductCode(PRODUCT_CODE);
    $inventory->setLocationCode(LOCATION_CODE);
    $inventory->setSku(INVENTORY_SKU);
    $inventories[] = $inventory;

    $inventory = new Inventory\Delete();
    $inventory->setProductCode(PRODUCT_CODE);
    $inventory->setLocationCode(LOCATION_CODE);
    $inventory->setSku(INVENTORY_SKU_ANOTHER);
    $inventories[] = $inventory;

    $inventory = new Inventory\Delete();
    $inventory->setProductCode(PRODUCT_CODE_SECOND);
    $inventory->setLocationCode(LOCATION_CODE);
    $inventory->setSku(INVENTORY_SKU_SECOND);
    $inventories[] = $inventory;

    return $inventories;
}

/**
 * @return Product\Create[]
 *
 * @throws InvalidDataTypeException
 */
function provideSampleProducts()
{
    $products = [];

    $product = new Product\Create();
    $product->setName(new Product\Dto\Name(['en-us' => 'Product Name']))
        ->setCode(PRODUCT_CODE)
        ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
        ->setIsInventoryManaged(true);

    $products[] = $product;

    $product = new Product\Create();
    $product->setName(new Product\Dto\Name(['en-us' => 'Product Name Second']))
        ->setCode(PRODUCT_CODE_SECOND)
        ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
        ->setIsInventoryManaged(true);

    $products[] = $product;

    return $products;
}

/**
 * @return array
 *
 * @throws InvalidDataTypeException
 */
function provideLocations()
{
    return [
        new Location\Create([
            'code' => LOCATION_CODE,
            'name' => 'Warehouse 1',
            'status' => 'active',
            'latitude' => 47.117330,
            'longitude' => 20.681810,
            'type' => [
                'code' => 'warehouse'
            ]
        ])
    ];
}

/**
 * @return array
 *
 * @throws InvalidDataTypeException
 */
function provideSampleOrders()
{
    $order = new Order\Create([
        'customerId' => 'customer-1',
        'localeCode' => 'en-us',
        'currencyCode' => 'USD',
        'primaryBillToAddressSequenceIndex' => 0,
        'subTotal' => 100,
        'total' => 100,
        'submitDate' => date('c'),
    ]);
    $order->setAddressSequences([
        new Order\Dto\Address([
            'type' => Order\Dto\Address::TYPE_BILLING,
            'firstName' => 'Jane',
            'lastName' => 'Doe',
        ])
    ]);
    $lineItem = new Order\Dto\LineItem([
        'code' => 'line-item-one',
        'quantity' => 1,
        'fulfillmentMethod' => Order\Dto\LineItem::FULFILLMENT_METHOD_DIRECT_SHIP,
        'shipToAddressSequenceIndex' => 0,
        'product' => new Order\Dto\LineItem\Product([
            'code' => 'product-one',
            'name' => 'Product One',
            'image' => 'https://mywebsite.com/images/product-one.jpg',
            'price' => 100,
            'currencyCode' => 'USD'
        ])
    ]);
    $order->setLineItems([$lineItem]);

    return [$order];
}
