<?php

namespace App;

class Customer {

  // constants for the Customer class
  const TYPE_VIP = 'VIP';
  const TYPE_REGULAR = 'Regular';
  const INVALID_TYPE_ERROR = 'Invalid customer type. Please use VIP or Regular.';

  // Private properties to store customer information
  private $name;
  private $phoneNumber;
  private $customerType;
  private $serviceNumber;

  /**
   * Constructor for the Customer class.
   *
   * @param string $name         The name of the customer.
   * @param string $phoneNumber  The phone number of the customer.
   * @param string $customerType The type of customer (e.g., 'VIP' or 'Regular').
   */
  public function __construct($name, $phoneNumber, $customerType) {
    // Initialize the customer properties with provided values
    $this->name = $name;
    $this->phoneNumber = $phoneNumber;
    $this->customerType = $customerType;

    // Validate and set the customer type
    if ($this->isValidCustomerType($customerType)) {
        $this->customerType = $customerType;
    } else {
        throw new \InvalidArgumentException(self::INVALID_TYPE_ERROR);
    }
    
    // Initialize the serviceNumber property to null
    $this->serviceNumber = null;
  }

  /**
   * Get the name of the customer.
   *
   * @return string The name of the customer.
   */
  public function getName() {
      return $this->name;
  }

  /**
   * Get the phone number of the customer.
   *
   * @return string The phone number of the customer.
   */
  public function getPhoneNumber() {
      return $this->phoneNumber;
  }

  /**
   * Get the customer type (e.g., 'VIP' or 'Regular').
   *
   * @return string The customer type.
   */
  public function getCustomerType() {
      return $this->customerType;
  }

  /**
   * Get the service number associated with the customer.
   *
   * @return mixed The service number or null if not set.
   */
  public function getServiceNumber() {
      return $this->serviceNumber;
  }

  /**
   * Check if the provided customer type is valid.
   *
   * @param string $customerType The customer type to validate.
   *
   * @return bool True if the customer type is valid, false otherwise.
   */
  private function isValidCustomerType($customerType) {
    // Define valid customer types
    $validCustomerTypes = [self::TYPE_VIP, self::TYPE_REGULAR];

    // Check if the provided customer type is in the list of valid types
    return in_array($customerType, $validCustomerTypes);
  }

}
