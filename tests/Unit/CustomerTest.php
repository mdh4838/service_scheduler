<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Customer;

class CustomerTest extends TestCase {

    public function testValidCustomerType() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_VIP);
        $this->assertEquals(Customer::TYPE_VIP, $customer->getCustomerType());
    }

    public function testInvalidCustomerType() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(Customer::INVALID_TYPE_ERROR);

        new Customer('John Doe', '123-456-7890', 'InvalidType');
    }

    public function testGetName() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_REGULAR);
        $this->assertEquals('John Doe', $customer->getName());
    }

    public function testGetPhoneNumber() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_REGULAR);
        $this->assertEquals('123-456-7890', $customer->getPhoneNumber());
    }

    public function testGetServiceNumberIsNull() {
        $customer = new Customer('John Doe', '123-456-7890', Customer::TYPE_REGULAR);
        $this->assertNull($customer->getServiceNumber());
    }
}
