# Shopgate Connect Integration SDK

[![Build Status](https://travis-ci.org/shopgate/connect-integration-sdk-php.svg?branch=master)](https://travis-ci.org/shopgate/connect-integration-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/shopgate/connect-integration-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/shopgate/connect-integration-sdk-php?branch=master)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
[![GitHub (pre-)release](https://img.shields.io/github/release/shopgate/connect-integration-sdk-php/all.svg)](https://github.com/shopgate/connect-integration-sdk-php/releases)
[![Semver](http://img.shields.io/SemVer/2.0.0.png?color=blue)](http://semver.org/spec/v2.0.0.html)

The Shopgate Connect Integration SDK is a compilation of classes to manage the communication between your shop system and Shopgate Connect.

Create a developer account at https://developer.shopgate.com

## Requirements
* PHP 5.6 and above

## Getting Started
#### Via Composer
```composer require shopgate/connect-integration-sdk-php```


#### Usage
Example for calling our service in order to create, update or delete a category:
```php
<?php
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Dto\Catalog\Category;

$config = [
    'merchantCode'  => 'EE1',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx'
];

$sgSdk = new ShopgateSdk($config);

// create new category
$categoryPayload = new Category\Create();
$name            = new Category\Dto\Name(['en-us' => 'Denim Pants']);
$categoryPayload->setCode('pants')->setName($name)->setSequenceId(1);
$sgSdk->getCatalogService()->addCategories([$categoryPayload]);
// update category with constructor input example
$updateDto = new Category\Update(['name' => 'Skirts']);
$sgSdk->getCatalogService()->updateCategory('pants', $updateDto);
// delete category
$sgSdk->getCatalogService()->deleteCategory('pants');
// get categories
$categories = $sgSdk->getCatalogService()->getCategories(['limit' => 5]);

// update category sync
$name      = (new Category\Dto\Name())->add('en-us', 'Skirts');
$updateDto = new Category\Update(['name' => $name]);
$sgSdk->getCatalogService()->updateCategory('4', $updateDto, ['requestType' => 'direct']);
```

Example for calling our service in order to create, update or delete a simple product:
```php
<?php
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Dto\Catalog\Product;
use Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Price as PriceDto;

$config = [
    'merchantCode'  => 'EE1',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx'
];

$sgSdk = new ShopgateSdk($config);
// create new price
$price = new PriceDto();
$price->setPrice(90)->setSalePrice(84.99)->setCurrencyCode(PriceDto::CURRENCY_CODE_EUR);
// create new product
$productPayload = new Product\Create();
$name = new Product\Dto\Name(['en-us' => 'Blue Jeans regular']);
$productPayload->setCode('42')
               ->setCatalogCode('my_catalog')
               ->setName($name)
               ->setStatus(Product\Create::STATUS_ACTIVE)
               ->setModelType(Product\Create::MODEL_TYPE_STANDARD)
               ->setIsInventoryManaged(true)
               ->setPrice($price);
$sgSdk->getCatalogService()->addProducts([$productPayload]);
// update product with constructor input example
$updateDto = new Product\Update(['name' => new Product\Dto\Name(['en-us' => 'Blue Jeans regular'])]);
$sgSdk->getCatalogService()->updateProduct('42', $updateDto);
// delete product
$sgSdk->getCatalogService()->deleteProduct('42');

// update product sync
$updateDto = new Product\Update(['status' => Product\Create::STATUS_INACTIVE]);
$sgSdk->getCatalogService()->updateProduct('42', $updateDto, ['requestType' => 'direct']);
```
Example for create bulk import:

```php
<?php

use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Dto\Catalog\Category;

$config = [
    'merchantCode'  => 'EE1',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx'
];

$sgSdk = new ShopgateSdk($config);

// create new category 1
$categoryPayload1 = new Category\Create();
$name1            = new Category\Dto\Name(['en-us' => 'Denim Pants']);
$categoryPayload1->setCode('pants')->setName($name1)->setSequenceId(1);

// create new category 2
$categoryPayload2 = new Category\Create();
$name2            = new Category\Dto\Name(['en-us' => 'Denim Shirts']);
$categoryPayload2->setCode('shirts')->setName($name1)->setSequenceId(1);

// init stream import
//$handler = $sgSdk->getBulkImportService()->createStreamImport();

// init file import
$handler = $sdk->getBulkImportService()->createFileImport();

// create product feed
// $productHandler = $handler->createProductFeed('8000');

// create category feed
$categoryHandler = $handler->createCategoryFeed('8000');

// add payloads
$categoryHandler->add($categoryPayload1);
$categoryHandler->add($categoryPayload2);

// submit items / stop stream
$categoryHandler->end();

// trigger import
$handler->trigger();
```

#### Config

* __clientId__ (string) - oAuth2 client ID
* __clientSecret__ (string) - oAuth2 client secret
* __merchantCode__ (string) - the following are template system related, the default _base_uri_ provided takes in variables in __{merchantCode}__ format. These params just replace these variables. This way you do not need to rewrite the base_uri, but just provide the correct variables to replace the template components.
* __base_uri__ (string, default: _https://{service}.shopgate{env}.services/v{ver}/merchants/{merchantCode}/_) - if rewriting, make sure to add a forward slash at end as the calls will append paths
* __ver__ (integer, default: 1) - template variable, can be substituted on a per call level in the meta array parameters
* __env__ (string, default: '') - template variable, can also be 'dev' or 'pg'

## Changelog

See [CHANGELOG.md](CHANGELOG.md) file for more information.

## Contributing

See [CONTRIBUTING.md](docs/CONTRIBUTING.md) file for more information.

## About Shopgate

Shopgate is the leading mobile commerce platform.

## License

The Shopgate Connect Integration SDK is available under the Apache License, Version 2.0.

See the [LICENSE.md](LICENSE.md) file for more information.

[Guzzle]:http://docs.guzzlephp.org/en/stable/request-options.html
