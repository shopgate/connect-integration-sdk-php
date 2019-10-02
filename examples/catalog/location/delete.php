<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;

try {
    $sdk->getClient()->doRequest(
        [
            // general
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            'query'       => [],
            // direct
            'method'      => 'delete',
            'service'     => 'location',
            'path'        => 'locations/' . LOCATION_CODE,
        ]
    );
} catch (Exception $exception) {
    echo $exception->getMessage();
}
