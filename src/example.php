<?php

use Shopgate\CloudIntegrationSdk\Client;
use Shopgate\CloudIntegrationSdk\Repository\Config;
use Shopgate\CloudIntegrationSdk\Service\Request;





$config = new Config\Config();
$guzzleClient = new Client\GuzzleHTTP(
    Client\ClientInterface::AUTHENTICATION_TYPE_BASIC,
    $config
);

$omniService = new Request\Omni(
    $guzzleClient,
    $config
);

$response = $omniService->updateProduct(1, ['name' => 'test']);
