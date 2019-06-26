<?php
/**
 * Created by PhpStorm.
 * User: alexanderwesselburg
 * Date: 26.06.19
 * Time: 13:15
 */

namespace Shopgate\ConnectSdk\Tests\Integration;

class CustomerTest extends ShopgateSdkTest
{
    const CUSTOMER_SERVICE           = 'omni-customer';
    const METHOD_DELETE_ATTRIBUTE    = 'deleteAttribute';
    const METHOD_DELETE_REQUEST_META = [
        self::METHOD_DELETE_ATTRIBUTE => [],
    ];

    public function setUp()
    {
        parent::setUp();

        $this->registerForCleanUp(
            self::CUSTOMER_SERVICE,
            $this->sdk->getCustomerService(),
            [
                self::METHOD_DELETE_ATTRIBUTE,
            ]
        );
    }
}
