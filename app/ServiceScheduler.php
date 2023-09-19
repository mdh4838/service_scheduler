<?php
namespace App;

class ServiceScheduler {

  const VIP_CUSTOMERS_PROCESSING_RATE = 2;
  const BAD_CUSTOMER_ERROR_MESSAGE = '$customer must be an instance of Customer.';

  private $vipQueue = [];
  private $regularQueue = [];
  private $vipCustomersServed = 0;

  public function getVipQueue() {
    return $this->vipQueue;
  }

  public function getRegularQueue() {
      return $this->regularQueue;
  }

  public function checkIn($customer)
  {
      try {
          // check if $customer is an instance of Customer class
          if ($customer instanceof Customer) {
              if ($customer->getCustomerType() == 'VIP') {
                  array_push($this->vipQueue, $customer);
              } else {
                  array_push($this->regularQueue, $customer);
              }
          } else {
              throw new \InvalidArgumentException(self::BAD_CUSTOMER_ERROR_MESSAGE);
          }
      } catch (\InvalidArgumentException $e) {
          return $e;
      }
   
  }


  public function getNextCustomer() {
    if (!empty($this->vipQueue)) {
        return array_shift($this->vipQueue);
    } elseif (!empty($this->regularQueue)) {
        return array_shift($this->regularQueue);
    } else {
        return null;
    }
  }

  // Part 3
  // modified version of getNextCustomer
  public function getNextCustomerSatisfying2to1ProcessingRate() {
    if (!empty($this->regularQueue) && ($this->vipCustomersServed >= self::VIP_CUSTOMERS_PROCESSING_RATE || empty($this->vipQueue))) {
        // reset vip customers served
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