<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Customer;

class CustomerTest extends TestCase {

    // Test if a valid customer type is accepted
    public function testValidCustomerType() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_VIP);
        $this->assertEquals(Customer::TYPE_VIP, $customer->getCustomerType());
    }

    // Test if an invalid customer type results in an exception with the correct message
    public function testInvalidCustomerType() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(Customer::INVALID_TYPE_ERROR);

        new Customer('John Doe', '123-456-7890', 'InvalidType');
    }

    // Test if the 'getName' method returns the correct customer name
    public function testGetName() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_REGULAR);
        $this->assertEquals('John Doe', $customer->getName());
    }

    // Test if the 'getPhoneNumber' method returns the correct phone number
    public function testGetPhoneNumber() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_REGULAR);
        $this->assertEquals('123-456-7890', $customer->getPhoneNumber());
    }

    // Test if the 'getServiceNumber' method returns null when service number is not set
    public function testGetServiceNumberIsNull() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_REGULAR);
        $this->assertNull($customer->getServiceNumber());
    }
}
