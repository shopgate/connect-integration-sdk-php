<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;

/**
 * preconditions:
 * - a default catalog exists
 */

try {
    $locations = provideLocations();

    $sdk->getClient()->doRequest(
        [
            // general
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            'json'        => ['locations' => $locations],
            'query'       => [],
            // direct
            'method'      => 'post',
            'service'     => 'location',
            'path'        => 'locations',
        ]
    );
} catch (Exception $exception) {
    echo $exception->getMessage();
}
