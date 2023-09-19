<?php

namespace App;

class Customer {
  private $name;
  private $phoneNumber;
  private $customerType;
  private $serviceNumber;

  public function __construct($name, $phoneNumber, $customerType) {
    $this->name = $name;
    $this->phoneNumber = $phoneNumber;
    $this->customerType = $customerType;
    $this->serviceNumber = null;
  }

  public function getName() {
      return $this->name;
  }

  public function getPhoneNumber() {
      return $this->phoneNumber;
  }

  public function getCustomerType() {
      return $this->customerType;
  }

  public function getServiceNumber() {
      return $this->serviceNumber;
  }
}