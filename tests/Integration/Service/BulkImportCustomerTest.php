<?php

namespace Shopgate\ConnectSdk\Tests\Integration\Http;

use Shopgate\ConnectSdk\Exception\Exception;
use Shopgate\ConnectSdk\Tests\Integration\CustomerTest;

class BulkImportCustomerTest extends CustomerTest
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
        $customerHandler = $handler->createCustomerFeed(self::SAMPLE_CATALOG_CODE);
        $customerHandler->add($customers[0]);
        $customerHandler->add($customers[1]);
        $customerHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CUSTOMER_SERVICE, self::METHOD_DELETE_CUSTOMER, [
            //todo-sg: figure out how we get the created ids
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableCustomers = $this->sdk->getCustomerService()->getCustomers();
        $this->assertCount(2, $availableCustomers->getCustomers());
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
        $customerHandler = $handler->createCustomerFeed(self::SAMPLE_CATALOG_CODE);
        $customerHandler->add($customers[0]);
        $customerHandler->add($customers[1]);
        $customerHandler->end();
        $handler->trigger();

        // CleanUp
        $this->deleteEntitiesAfterTestRun(self::CUSTOMER_SERVICE, self::METHOD_DELETE_CUSTOMER, [
            //todo-sg: figure out how we get the created ids
        ]);

        usleep(self::SLEEP_TIME_AFTER_BULK);

        // Assert
        $availableCustomers = $this->sdk->getCustomerService()->getCustomers();
        $this->assertCount(2, $availableCustomers->getCustomers());
    }
}
