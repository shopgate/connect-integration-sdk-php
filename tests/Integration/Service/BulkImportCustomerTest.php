<?php

namespace Shopgate\ConnectSdk\Tests\Integration\Service;

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CustomerUtility;

class BulkImportCustomerTest extends CustomerUtility
{
    const SLEEP_TIME_AFTER_BULK = 12000000;

    /**
     * @throws Exception
     */
    public function testCustomerBulkFileImport()
    {
        // Arrange
        $customers = $this->provideSampleCustomers();

        // Act
        $handler = $this->sdk->getBulkImportService()->createFileImport();
        $customerHandler = $handler->createCustomerFeed();
        $customerHandler->add($customers[0]);
        $customerHandler->add($customers[1]);
        $customerHandler->end();
        $handler->trigger();

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableCustomers = $this->sdk->getCustomerService()->getCustomers();

        // CleanUp
        $deleteIds = [];
        foreach ($availableCustomers->getCustomers() as $customer) {
            $deleteIds[] = $customer->getId();
        }
        $this->deleteEntitiesAfterTestRun(self::CUSTOMER_SERVICE, self::METHOD_DELETE_CUSTOMER, $deleteIds);

        $this->assertCount(2, $availableCustomers->getCustomers());

        foreach ($availableCustomers->getCustomers() as $availableCustomer) {
            $fullCustomer = $this->sdk->getCustomerService()->getCustomer($availableCustomer->getId());

            $this->assertCount(1, $fullCustomer->getContacts());
            $this->assertEquals(self::CUSTOMER_CONTACT_FIRSTNAME, $fullCustomer->getContacts()[0]->getFirstName());
        }
    }

    /**
     * @throws Exception
     */
    public function testCustomerBulkStreamImport()
    {
        // Arrange
        $customers = $this->provideSampleCustomers();

        // Act
        $handler = $this->sdk->getBulkImportService()->createStreamImport();
        $customerHandler = $handler->createCustomerFeed();
        $customerHandler->add($customers[0]);
        $customerHandler->add($customers[1]);
        $customerHandler->end();
        $handler->trigger();

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableCustomers = $this->sdk->getCustomerService()->getCustomers();
        $deleteIds = [];
        foreach ($availableCustomers->getCustomers() as $customer) {
            $deleteIds[] = $customer->getId();
        }

        $this->deleteEntitiesAfterTestRun(self::CUSTOMER_SERVICE, self::METHOD_DELETE_CUSTOMER, $deleteIds);

        $this->assertCount(2, $availableCustomers->getCustomers());
    }
}
