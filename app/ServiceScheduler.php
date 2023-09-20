<?php
namespace App;

class ServiceScheduler {

  // Constants for the ServiceScheduler class
  const VIP_CUSTOMERS_PROCESSING_RATE = 2;
  const INVALID_CUSTOMER_ERROR_MESSAGE = '$customer must be an instance of Customer.';

  // Queues to store VIP and Regular customers
  private $vipQueue = [];
  private $regularQueue = [];

  // Counter to keep track of VIP customers served
  private $vipCustomersServed = 0;

  /**
   * Get the VIP customer queue.
   *
   * @return array The VIP customer queue.
   */
  public function getVipQueue() {
    return $this->vipQueue;
  }

  /**
   * Get the regular customer queue.
   *
   * @return array The regular customer queue.
   */
  public function getRegularQueue() {
      return $this->regularQueue;
  }

  /**
   * Check in a customer to the appropriate queue based on customer type.
   *
   * @param mixed $customer The customer to check in.
   *
   * @return \InvalidArgumentException|null An exception or null if successful check-in.
   */
  public function checkIn($customer)
  {
      try {
          // Check if $customer is an instance of Customer class
          if ($customer instanceof Customer) {
              // Determine customer type and add to the respective queue
              if ($customer->getCustomerType() == Customer::TYPE_VIP) {
                  array_push($this->vipQueue, $customer);
              } else {
                  array_push($this->regularQueue, $customer);
              }
          } else {
              // Throw an exception if $customer is not an instance of Customer
              throw new \InvalidArgumentException(self::INVALID_CUSTOMER_ERROR_MESSAGE);
          }
      } catch (\InvalidArgumentException $e) {
          // Catch and return the exception
          return $e;
      }
  }

  /**
   * Get the next customer from either the VIP or regular queue.
   *
   * @return mixed|null The next customer or null if no customers are in the queues.
   */
  public function getNextCustomer() {
    if (!empty($this->vipQueue)) {
        return array_shift($this->vipQueue);
    } elseif (!empty($this->regularQueue)) {
        return array_shift($this->regularQueue);
    } else {
        return null;
    }
  }

  /**
   * Get the next customer based on the 2-to-1 (VIP vs Regular) processing rate.
   *
   * @return mixed|null The next customer or null if no customers match the rate.
   */
  public function getNextCustomerSatisfying2to1ProcessingRate() {
    if (!empty($this->regularQueue) && ($this->vipCustomersServed >= self::VIP_CUSTOMERS_PROCESSING_RATE || empty($this->vipQueue))) {
        // Reset VIP customers served counter
        $this->vipCustomersServed = 0;
        return array_shift($this->regularQueue);
    } elseif(!empty($this->vipQueue)) {
        $this->vipCustomersServed++;
        return array_shift($this->vipQueue);
    } else {
        return null;
    }
  }
}
