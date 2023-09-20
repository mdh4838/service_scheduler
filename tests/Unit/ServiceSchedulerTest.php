<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Customer as Customer;
use App\ServiceScheduler as ServiceScheduler;

class ServiceSchedulerTest extends TestCase
{
    private $scheduler;

    protected function setup(): void {
        parent::setup();
        $this->scheduler = new ServiceScheduler();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->scheduler);
    }

    // Helper function to check in customers
    private function checkInCustomers(...$customers)
    {
        foreach ($customers as $customer) {
            $this->scheduler->checkIn($customer);
        }
    }

    // Test invalid customer check-in
    public function testCheckInWithInvalidCustomer()
    {
        $customer = 'Invalid Customer Data';
        $result = $this->scheduler->checkIn($customer);

        $this->assertInstanceOf(\InvalidArgumentException::class, $result);

        $this->assertEquals(ServiceScheduler::INVALID_CUSTOMER_ERROR_MESSAGE, $result->getMessage());

        // assert queues are empty
        $this->assertCount(0, $this->scheduler->getVipQueue());
        $this->assertCount(0, $this->scheduler->getRegularQueue());
    }

    /**
     * @dataProvider dataForCheckInAddsToCorrectQueue
     */
    public function testCheckInAddsToCorrectQueue($vipCustomer, $regularCustomer, $expectedVipCount, $expectedRegularCount)
    {

        $this->scheduler->checkIn($vipCustomer);
        $this->scheduler->checkIn($regularCustomer);

        $this->assertCount($expectedVipCount, $this->scheduler->getVipQueue());
        $this->assertCount($expectedRegularCount, $this->scheduler->getRegularQueue());
    }

    /**
     * @dataProvider dataForGetNextCustomerReturnsCustomersInOrder
     */
    public function testGetNextCustomerReturnsCustomersInOrder($inputCustomers, $expectedCustomers)
    {

        $this->checkInCustomers(...$inputCustomers);

        foreach ($expectedCustomers as $expectedCustomer) {
            $this->assertSame($expectedCustomer, $this->scheduler->getNextCustomer());
        }

    }

    public function testGetNextCustomerReturnsNullIfNoCustomers()
    {
        $this->assertNull($this->scheduler->getNextCustomer());
    }

    /**
     * @dataProvider dataForGetNextCustomerSatisfying2to1ProcessingRate
     */
    public function testGetNextCustomerSatisfying2to1ProcessingRate($inputCustomers, $expectedCustomers)
    {
        $this->checkInCustomers(...$inputCustomers);

        foreach ($expectedCustomers as $expectedCustomer) {
          $c = $this->scheduler->getNextCustomerSatisfying2to1ProcessingRate();
          $this->assertSame($expectedCustomer, $c);
        }
    }

    // Data provider for the testCheckInAddsToCorrectQueue test
    public static function dataForCheckInAddsToCorrectQueue()
    {
        return [
            [new Customer('John Doe', '123-456-7890', Customer::TYPE_VIP), new Customer('Jane Smith', '987-654-3210', Customer::TYPE_REGULAR), 1, 1],
        ];
    }

    // Data provider for the testGetNextCustomerReturnsCustomersInOrder test
    public static function dataForGetNextCustomerReturnsCustomersInOrder()
    {
        $c1 = new Customer('John Doe', '111-111-1111', Customer::TYPE_VIP);
        $c2 = new Customer('Mary Yi', '333-333-3333', Customer::TYPE_REGULAR);
        $c3 = new Customer('Jane Smith', '222-222-2222', Customer::TYPE_VIP);
        $c4 = new Customer('Tom Barry', '444-444-4444', Customer::TYPE_REGULAR);

        return [
            [
                [ $c1, $c2, $c3, $c4 ], 
                [ $c1, $c3, $c2, $c4 ]
            ]
        ];
    }

    // Data provider for the testGetNextCustomerSatisfying2to1ProcessingRate test
    public static function dataForGetNextCustomerSatisfying2to1ProcessingRate()
    {
        $c1 = new Customer('John Doe', '111-111-1111', Customer::TYPE_VIP);
        $c2 = new Customer('Jane Smith', '222-222-2222', Customer::TYPE_VIP);
        $c3 = new Customer('John Garrigan', '989-222-2222', Customer::TYPE_VIP);
        $c4 = new Customer('Mary Kessler', '333-333-3333', Customer::TYPE_REGULAR);
        $c5 = new Customer('Tom Barry', '444-444-4444', Customer::TYPE_REGULAR);
        $c6 = new Customer('Adam Sandler', '133-444-4444', Customer::TYPE_REGULAR);
        $c7 = new Customer('Tom Brady', '888-444-4444', Customer::TYPE_REGULAR);

        return [
            [
                [ $c1, $c2, $c3, $c4, $c5, $c6, $c7 ], 
                [ $c1, $c2, $c4, $c3, $c5, $c6, $c7 ]
            ]
        ];
    }
}
