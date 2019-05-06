# Shopgate Connect Integration SDK

[![GitHub license](http://dmlc.github.io/img/apache2.svg)](LICENSE.md)
[![Build Status](https://travis-ci.org/shopgate/connect-integration-sdk-php.svg?branch=master)](https://travis-ci.org/shopgate/connect-integration-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/shopgate/connect-integration-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/shopgate/connect-integration-sdk-php?branch=master)

The Shopgate Connect Integration SDK is a compilation of classes to manage the communication between your shop system and Shopgate Connect.

## Getting Started
#### Via Composer
```composer require shopgate/connect-integration-sdk-php```


#### Usage
Example for calling a service in order to update the name of the category using the Guzzle client and basic authentication:
```
<?php
use Shopgate\ConnectSdk\Services\Events\Client;

$config = [
    'http' => [
        'base_uri'     => 'https://{service}.shopgate.services/v1/merchants/{merchantCode}/',
        'auth'         => ['username', 'password'],
        'merchantCode' => 'EE1',
        'service'      => 'omni-event-receiver'
    ],
];

$client = new Client($config);

// update category
$meta = ['catalogCode' => 'cat1', 'language' => 'de-de'];
$client->catalog->updateCategory('4', ['title' => 'Skirts'], $meta);
// create category
$client->catalog->createCategory(['title' => 'Blue Jeans', 'code'=>'pants', 'sequenceId' => 1], $meta);
// delete category
$client->catalog->deleteCategory('pants', $meta);

// update category sync (not currently functional)
$meta = ['catalogCode' => 'cat1', 'language' => 'de-de', 'requestType' => 'direct'];
$client->catalog->updateCategory('4', ['title' => 'Skirts'], $meta);
```

#### Config

* __http__ (array, default: []) - these configurations are passed down to the default HTTP Client, which is Guzzle. See [Guzzle documentation] for more information.
  * __base_uri__ (string, default: _https://{service}.shopgate{env}.services/v{ver}/merchants/{merchantCode}/_) - if rewriting, make sure to add a forward slash at end as the calls will append paths
  * __auth__ (array) - see [Guzzle auth] documentation for more info on passing auth data
  * __handler__ (GuzzleHttp\HandlerStack) - see [Guzzle handler] documentation for using handlers
  * __merchantCode__ (string) - this is actually not Guzzle related, but template system related. The _base_uri_ provided above takes in variables in __{merchantCode}__ format. These params just replace these variables. This way you do not need to rewrite the base_uri, but just provide the correct variables to replace the template components.
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

[Guzzle auth]:http://docs.guzzlephp.org/en/stable/request-options.html#auth
[Guzzle handler]: http://docs.guzzlephp.org/en/stable/handlers-and-middleware.html
[Guzzle documentation]:http://docs.guzzlephp.org/en/stable/request-options.html
