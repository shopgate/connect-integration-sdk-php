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

use Shopgate\ConnectSdk\Http\GuzzleClient;
use Shopgate\ConnectSdk\Services\OER\Client;

$config = [
    'http' => [
        'auth' => ['username', 'password']
    ]
];

$client = new Client($config);

// update category
$meta = ['catalogCode' => 'cat1', 'language' => 'de-de'];
$client->catalog->update($catalogId, ['name' => 'New category name'], $meta);
// update category sync
$meta = ['catalogCode' => 'cat1', 'language' => 'de-de', 'requestType' => 'direct'];
$client->catalog->update($catalogId, ['name' => 'New category name'], $meta);
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) file for more information.

## Contributing

See [CONTRIBUTING.md](docs/CONTRIBUTING.md) file for more information.

## About Shopgate

Shopgate is the leading mobile commerce platform.

## License

The Shopgate Connect Integration SDK is available under the Apache License, Version 2.0.

See the [LICENSE.md](LICENSE.md) file for more information.
