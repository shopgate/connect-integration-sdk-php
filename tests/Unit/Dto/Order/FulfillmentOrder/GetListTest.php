<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\FulfillmentOrder;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\GetList;
use Shopgate\ConnectSdk\Dto\Order\SimpleFulfillmentOrder;

class GetListTest extends TestCase
{
    public function testCategoryDto()
    {
        // Arrange
        $entry = [
            'meta' => [
                'limit' => 1,
            ],
            'fulfillmentOrders' => [
                [
                    'orderNumber' => 'testOrderNumber',
                    'externalCode' => 'testExternalCode',
                    'posTransactionId' => '528a-1',
                    'cancellationReason' => FulfillmentOrder::CANCELLATION_EXPIRED,
                    'salesOrderNumber' => '1293747',
                    'locationCode' => 'DERetail001',
                    'type' => FulfillmentOrder::TYPE_DIRECT_SHIP,
                    'customerId' => '55c98b8e-1100-497c-8df6-4b4f0353ab2a',
                    'externalCustomerNumber' => 'C1756793',
                    'routeType' => FulfillmentOrder::ROUTE_TYPE_STANDARD_DIRECT_SHIP,
                    'expedited' => true,
                    'status' => FulfillmentOrder::STATUS_NEW,
                    'taxAmount' => 0,
                    'tax2Amount' => 0,
                    'total' => 0,
                    'shippingTotal' => 0,
                    'localeCode' => 'en-us',
                    'currencyCode' => 'USD',
                    'notes' => 'Some note',
                    'specialInstructions' => '',
                    "orderSubmittedDate" => "2019-09-04T07:26:42.535Z",
                    "acceptedDate" => "2019-09-04T07:26:42.535Z",
                    "readyDate" => "2019-09-04T07:26:42.535Z",
                    "completedDate" => "2019-09-04T07:26:42.535Z"
                ]
            ]
        ];

        // Act
        $getList = new GetList($entry);
        $fulfillmentOrders = $getList->getFulfillmentOrders();
        $get = $fulfillmentOrders[0];

        // Assert
        $this->assertInstanceOf(Meta::class, $getList->getMeta());
        $this->assertEquals(1, $getList->getMeta()->getLimit());
        $this->assertCount(1, $fulfillmentOrders);
        $this->assertInstanceOf(SimpleFulfillmentOrder::class, $get);
        $this->assertEquals($entry['fulfillmentOrders'][0]['orderNumber'], $get->getOrderNumber());
        $this->assertEquals($entry['fulfillmentOrders'][0]['externalCode'], $get->getExternalCode());
        $this->assertEquals($entry['fulfillmentOrders'][0]['posTransactionId'], $get->getPosTransactionId());
        $this->assertEquals($entry['fulfillmentOrders'][0]['cancellationReason'], $get->getCancellationReason());
        $this->assertEquals($entry['fulfillmentOrders'][0]['salesOrderNumber'], $get->getSalesOrderNumber());
        $this->assertEquals($entry['fulfillmentOrders'][0]['locationCode'], $get->getLocationCode());
        $this->assertEquals($entry['fulfillmentOrders'][0]['type'], $get->getType());
        $this->assertEquals($entry['fulfillmentOrders'][0]['customerId'], $get->getCustomerId());
        $this->assertEquals($entry['fulfillmentOrders'][0]['externalCustomerNumber'], $get->getExternalCustomerNumber());
        $this->assertEquals($entry['fulfillmentOrders'][0]['routeType'], $get->getRouteType());
        $this->assertEquals($entry['fulfillmentOrders'][0]['expedited'], $get->getExpedited());
        $this->assertEquals($entry['fulfillmentOrders'][0]['status'], $get->getStatus());
        $this->assertEquals($entry['fulfillmentOrders'][0]['taxAmount'], $get->getTaxAmount());
        $this->assertEquals($entry['fulfillmentOrders'][0]['tax2Amount'], $get->getTax2Amount());
        $this->assertEquals($entry['fulfillmentOrders'][0]['total'], $get->getTotal());
        $this->assertEquals($entry['fulfillmentOrders'][0]['shippingTotal'], $get->getShippingTotal());
        $this->assertEquals($entry['fulfillmentOrders'][0]['localeCode'], $get->getLocaleCode());
        $this->assertEquals($entry['fulfillmentOrders'][0]['currencyCode'], $get->getCurrencyCode());
        $this->assertEquals($entry['fulfillmentOrders'][0]['notes'], $get->getNotes());
        $this->assertEquals($entry['fulfillmentOrders'][0]['specialInstructions'], $get->getSpecialInstructions());
    }
}
