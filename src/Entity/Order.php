<?php namespace App\Entity; 

Class Order {

    private $order_id;
    private $order_date;
    private $discounts;
    private $shipping_price;
    private $total_order_value;
    private $items;
    private $total_unit;
    private $customer;

    public function __construct($order_id, $order_date, $discounts, $shipping_price, $items)
    {
        $this->order_id = $order_id;
        $this->order_date = $order_date;
        $this->discounts = $discounts;
        $this->shipping_price = $shipping_price;
        $this->items = $items;        
    }

    public function setCustomer($customer) {
        
        $this->customer = new Customer($customer["customer_id"], $customer["first_name"], $customer["last_name"], $customer["email"], $customer["phone"]);
        $this->customer->setShippingAddress($customer["shipping_address"]);
    }

    public function getCustomer() {
        if ($this->customer === null) {
            $this->customer = new Customer;
        }

        return $this->customer;
    }

    public function getOrderId(){
        return $this->order_id;
    }

    public function getOrderDate(){
        return $this->order_date;
    }

    public function sortDiscounts(){
        array_multisort(array_map(function($element) {
            return $element['priority'];
        }, $this->discounts), SORT_ASC, $this->discounts);

    }

    public function setDiscount(){
        $this->sortDiscounts();
        foreach($this->discounts as $discount){
            if ($discount["type"] == "DOLLAR"){
                $this->total_order_value -= $discount["value"];
            }elseif($discount["type"] == "PERCENTAGE"){
                $this->total_order_value -=  $this->total_order_value * $discount["value"] / 100;
            }
        }
    }

    public function orderValue(){
        $this->total_unit = 0;
        $this->total_order_value = 0;
        foreach($this->items as $item){
            $this->total_unit += $item['quantity'];
            $this->total_order_value += $item['quantity'] * $item['unit_price'];
        }
    }
    

    public function getTotalOrderValue(){
        $this->orderValue();
        $this->setDiscount();
        
        return '$' . number_format($this->total_order_value - $this->shipping_price, 2);
    }

    public function getAvarageUnitPrice(){
        $this->orderValue();
        return '$' . number_format($this->total_order_value / $this->total_unit, 2);
    }

    public function countDistinctUnit(){
        return count($this->items);
    }

    public function getTotalUnit(){
        return $this->total_unit;
    }

}