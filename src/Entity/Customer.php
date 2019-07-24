<?php namespace App\Entity; 

Class Customer {

    private $customer_id;
    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $shipping_address;

    public function __construct($customer_id, $first_name, $last_name, $email, $phone)
    {
        $this->customer_id = $customer_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        
    }

    public function setShippingAddress($address) {
        
        $this->shipping_address = new Address($address["street"], $address["postcode"], $address["suburb"], $address["state"]);

    }

    public function getShippingAddress() {
        if ($this->shipping_address === null) {
            $this->shipping_address = new Address;
        }

        return $this->shipping_address;
    }

    public function getShippingState() {
        return $this->shipping_address->getState();
    }

}