# Shopgate Connect Integration SDK

[![Build Status](https://travis-ci.org/shopgate/connect-integration-sdk-php.svg?branch=master)](https://travis-ci.org/shopgate/connect-integration-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/shopgate/connect-integration-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/shopgate/connect-integration-sdk-php?branch=master)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
[![GitHub (pre-)release](https://img.shields.io/github/release/shopgate/connect-integration-sdk-php/all.svg)](https://github.com/shopgate/connect-integration-sdk-php/releases)

The Shopgate Connect Integration SDK is a compilation of classes to manage the communication between your shop system and Shopgate Connect.

Create a developer account at https://developer.shopgate.com

## Requirements
* PHP 5.6 and above

## Installation
```composer require shopgate/connect-integration-sdk-php```

Or download and unzip from the [releases page](https://github.com/shopgate/connect-integration-sdk-php/releases).

## Migration to 2.x
If you're upgrading from 1.x to 2.x, read the [migration guide](MIGRATION-GUIDE-2.x.md).

## Quick Start
Order creation example (see [Order API docs](https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/static.html?url=https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/order-crud.yaml#/SalesOrder/createSalesOrders) for full request spec):
```php
<?php
use Shopgate\ConnectSdk\ShopgateSdk;

$order = [
    'localeCode' => 'de-de',
    'currencyCode' => 'EUR',
    'addressSequences' => [...],
    'primaryBillToAddressSequenceIndex' => 0,
    'lineItems' => [...],
    'subTotal' => 109.99,
    'total' => 115.89,
    'submitDate' => '2019-09-02T09:02:57.733Z',
    'imported' => true
];

$config = [
    'merchantCode'  => 'MERCHANT_CODE',
    'clientId'      => 'my-client',
    'clientSecret'  => '*******',
    'username'      => 'my.address@my-domain.com',
    'password'      => '*******',
    'env'           => 'pg' // Optional. "dev", "pg" or empty (= production)
];

$sgSdk = new ShopgateSdk($config);

try {
    $response = $sgSdk->getOrderService()->addOrders([$order]);
    var_dump($resonse);
} catch (\Exception $e) {
    var_dump($e);
}
```

## Configuration Parameters
* __clientId__ (string) - oAuth2 client ID
* __clientSecret__ (string) - oAuth2 client secret
* __merchantCode__ (string) - merchant code provided to you upon registration
* __username__ - (string) - the email address of the user called "Api Credentials" at Shopgate Next Admin
* __password__ - (string) - the password of the user called "Api Credentials" at Shopgate Next Admin
* __env__ (string, default: _production_) - one of "dev", "staging", "production"

## Changelog
See [CHANGELOG.md](CHANGELOG.md) file for more information.

## Contributing
See [CONTRIBUTING.md](docs/CONTRIBUTING.md) file for more information.

## About Shopgate
Shopgate is the leading mobile commerce platform.

## License
The Shopgate Connect Integration SDK is available under the Apache License, Version 2.0.

See the [LICENSE.md](LICENSE.md) file for more information.

## Advanced
### "baseUri" Configuration
For testing against an echo service the __baseUri__ config can be overridden. It defaults to
`https://{service}.shopgate{env}.io/{version}/merchants/{merchantCode}/`, supporting template variables:

* __service__ - the service name, different for each request
* __env__ - the Shopgate environment-dependent domain suffix; this will automatically be replaced with one of
  "dev", "pg" (staging) or "" (production)
* __version__ - the API version, may be different for each request
* __merchantCode__ - the merchant code set in the configuration
