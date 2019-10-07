<?php


namespace Shopgate\ConnectSdk\Tests\Unit\Dto\Order\FulfillmentOrder;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\GetList;
use Shopgate\ConnectSdk\Dto\Order\SimpleFulfillmentOrder;
use Shopgate\ConnectSdk\Exception\Exception;

class GetListTest extends TestCase
{
    /**
     * @throws Exception
     */
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
                    'submitDate' => "2019-09-04T07:26:42.535Z",
                    'acceptDate' => "2019-09-04T07:26:42.535Z",
                    'readyDate' => "2019-09-04T07:26:42.535Z",
                    'completeDate' => "2019-09-04T07:26:42.535Z",
                    'fulfillmentOrderAddress' => [
                        "orderIndex" => "123",
                        "type" => "pickup",
                        "firstName" => "John",
                        "middleName" => "D.",
                        "lastName" => "Doe",
                        "company" => "Shopgate Inc",
                        "address1" => "12 Somestreet",
                        "address2" => "Unknown Type: string,null",
                        "address3" => "Unknown Type: string,null",
                        "address4" => "Unknown Type: string,null",
                        "city" => "Austin",
                        "region" => "TX",
                        "postalCode" => '78732',
                        "country" => "US",
                        "phone" => "+1 000-000-0000",
                        "fax" => "+1 000-000-0000",
                        "mobile" => "+1 000-000-0000",
                        "emailAddress" => "john@doe.com"
                    ],
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
        $this->assertEquals(
            $entry['fulfillmentOrders'][0]['externalCustomerNumber'],
            $get->getExternalCustomerNumber()
        );
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
        $this->assertEquals($entry['fulfillmentOrders'][0]['submitDate'], $get->getSubmitDate());
        $this->assertEquals($entry['fulfillmentOrders'][0]['acceptDate'], $get->getAcceptDate());
        $this->assertEquals($entry['fulfillmentOrders'][0]['readyDate'], $get->getReadyDate());
        $this->assertEquals($entry['fulfillmentOrders'][0]['completeDate'], $get->getCompleteDate());

        $fulfillmentOrderAddress = $get->getFulfillmentOrderAddress();
        $entryAddress = $entry['fulfillmentOrders'][0]['fulfillmentOrderAddress'];
        $this->assertInstanceOf(FulfillmentOrder\Dto\FulfillmentOrderAddress::class, $fulfillmentOrderAddress);
        $this->assertEquals($entryAddress['orderIndex'], $fulfillmentOrderAddress->getOrderIndex());
        $this->assertEquals($entryAddress['type'], $fulfillmentOrderAddress->getType());
        $this->assertEquals($entryAddress['firstName'], $fulfillmentOrderAddress->getFirstName());
        $this->assertEquals($entryAddress['middleName'], $fulfillmentOrderAddress->getMiddleName());
        $this->assertEquals($entryAddress['lastName'], $fulfillmentOrderAddress->getLastName());
        $this->assertEquals($entryAddress['company'], $fulfillmentOrderAddress->getCompany());
        $this->assertEquals($entryAddress['address1'], $fulfillmentOrderAddress->getAddress1());
        $this->assertEquals($entryAddress['address2'], $fulfillmentOrderAddress->getAddress2());
        $this->assertEquals($entryAddress['address3'], $fulfillmentOrderAddress->getAddress3());
        $this->assertEquals($entryAddress['address4'], $fulfillmentOrderAddress->getAddress4());
        $this->assertEquals($entryAddress['city'], $fulfillmentOrderAddress->getCity());
        $this->assertEquals($entryAddress['region'], $fulfillmentOrderAddress->getRegion());
        $this->assertEquals($entryAddress['postalCode'], $fulfillmentOrderAddress->getPostalCode());
        $this->assertEquals($entryAddress['country'], $fulfillmentOrderAddress->getCountry());
        $this->assertEquals($entryAddress['phone'], $fulfillmentOrderAddress->getPhone());
        $this->assertEquals($entryAddress['fax'], $fulfillmentOrderAddress->getFax());
        $this->assertEquals($entryAddress['mobile'], $fulfillmentOrderAddress->getMobile());
        $this->assertEquals($entryAddress['emailAddress'], $fulfillmentOrderAddress->getEmailAddress());
    }
}
