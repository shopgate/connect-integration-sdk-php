# Shopgate Connect Integration SDK

[![Build Status](https://travis-ci.org/shopgate/connect-integration-sdk-php.svg?branch=master)](https://travis-ci.org/shopgate/connect-integration-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/shopgate/connect-integration-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/shopgate/connect-integration-sdk-php?branch=master)
[![GitHub license](http://dmlc.github.io/img/apache2.svg)](LICENSE.md)
[![Semver](http://img.shields.io/SemVer/2.0.0.png?color=blue)](http://semver.org/spec/v2.0.0.html)

The Shopgate Connect Integration SDK is a compilation of classes to manage the communication between your shop system and Shopgate Connect.

Create a developer account at https://developer.shopgate.com

## Requirements
* PHP 5.5 and above

## Getting Started
#### Via Composer
```composer require shopgate/connect-integration-sdk-php```


#### Usage
Example for calling a service in order to update the name of the category using the Guzzle client and basic authentication:
```
<?php
use Shopgate\ConnectSdk\Services\Events\Client;
use Shopgate\ConnectSdk\Services\Events\DTO\V1\Payload\Catalog\Category as CategoryDto;

$config = [
    'http' => [
        'base_uri'     => 'https://{service}.shopgate.services/v1/merchants/{merchantCode}/',
        'auth'         => ['username', 'password'],
        'merchantCode' => 'EE1',
        'service'      => 'omni-event-receiver'
    ]
];

$client = new Client($config);

// create new category
$categoryPayload = new CategoryDto();
$categoryPayload->setCode('pants')->setName('Denim Pants')->setSequenceId(1);
$client->catalog->createCategory($categoryPayload);
// update category with constructor input example
$updateDto = new CategoryDto(['name' => 'Skirts']);
$client->catalog->updateCategory('pants', $updateDto);
// delete category
$client->catalog->deleteCategory('pants');

// update category sync
$updateDto = new CategoryDto(['name' => 'Skirts']);
$client->catalog->updateCategory('4', $updateDto, ['requestType' => 'direct']);
```

#### Config

* __http__ (array, default: []) - these configurations are passed down to the default HTTP Client, which is [Guzzle].
  * __base_uri__ (string, default: _https://{service}.shopgate{env}.services/v{ver}/merchants/{merchantCode}/_) - if rewriting, make sure to add a forward slash at end as the calls will append paths
  * __auth__ (array) - the simple usage is providing ['username', 'password'] for basic auth
  * __merchantCode__ (string) - the following are template system related, the default _base_uri_ provided above takes in variables in __{merchantCode}__ format. These params just replace these variables. This way you do not need to rewrite the base_uri, but just provide the correct variables to replace the template components.
  * __service__ (string, default: _omni-event-receiver_) - template variable
  * __ver__ (integer, default: 1) - template variable
  * __env__ (string, default: '') - template variable
* __http_client__ (Shopgate\ConnectSdk\Http\ClientInterface, default: GuzzleHttp\Client) - one could provide their own HTTP client if needed be, the http array would not be needed though, just pass your own config when instantiating.

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
