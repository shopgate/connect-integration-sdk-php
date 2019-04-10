# Shopgate Connect Integration SDK

[![GitHub license](http://dmlc.github.io/img/apache2.svg)](LICENSE.md)
[![Build Status](https://travis-ci.org/shopgate/connect-integration-sdk-php.svg?branch=master)](https://travis-ci.org/shopgate/connect-integration-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/shopgate/connect-integration-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/shopgate/connect-integration-sdk-php?branch=master)

The Shopgate Connect Integration SDK is a compilation of classes to manage the communication between your shop system and Shopgate Connect.

## Getting Started
#### Via Composer
```composer require shopgate/connect-integration-sdk-php```

#### Usage
Example for calling a service in order to update the name of the product using the guzzle client and basic authentication:
```
<?php

use Shopgate\CloudIntegrationSdk\Client;
use Shopgate\CloudIntegrationSdk\Service\Omni;
use Shopgate\CloudIntegrationSdk\Service\Omni\ValueObject\Event;

$config = [
    Client\GuzzleHttp::CONFIG_KEY_AUTHENTICATION => [
        Client\GuzzleHttp::CONFIG_KEY_AUTHENTICATION_USER => 'username',
        Client\GuzzleHttp::CONFIG_KEY_AUTHENTICATION_PASSWORD => 'password',
    ]
];

$client  = new Client\GuzzleHttp(Client\GuzzleHttp::AUTHENTICATION_TYPE_BASIC, $config);
$request = new Omni\Request($client, $config);

$request->update(Event::ENTITY_PRODUCT, 1, ['name' => 'New product name']);

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
