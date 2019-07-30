<?php

require_once(dirname(__FILE__) . '/../bootstrap.php');

use \Shopgate\ConnectSdk\ShopgateSdk;
use \Shopgate\ConnectSdk\Exception\Exception;

try {
    // TODO: currently throwing validation errors - work in progress
    $sdk->getClient()->doRequest(
        [
            // general
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            'json'        => [ 'orders' => [new \Shopgate\ConnectSdk\Dto\Base([
                'orderNumber' => '1234',
                'type' => 'string',
                'submitDate' => '31-07-2019-06:05:44',
                'total' => 12.23,
                'customerId' => '',
                'currencyCode' => 'USD',
                'primaryBillToAddressSequenceIndex' => 0,
                'lineItems' => [
                    [
                        'lineItemCode' => '1234',
                        'price' => 12.23
                    ]
                ]
            ])]],
            'query'       => [],
            // direct
            'method'      => 'post',
            'service'     => 'omni-order',
            'path'        => 'orders',
        ]
    );
} catch (Exception $exception) {
    echo $exception->getMessage();
}
