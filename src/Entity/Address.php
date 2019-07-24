<?php namespace App\Entity; 

Class Address {

    private $street;
    private $postcode;
    private $suburb;
    private $state;

    public function __construct($street, $postcode, $suburb, $state)
    {
        $this->street = $street;
        $this->postcode = $postcode;
        $this->suburb = $suburb;
        $this->state = $state;        
    }

    public function getState(){
        return $this->state;
    }
}