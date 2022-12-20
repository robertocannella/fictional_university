<?php

class Cart {
    public $customerId;
    public $items = [];
    private $cartId;
    private $count;

    public function __construct(){

        $this->cartId = uniqid($prefix = "cartId", $more_entropy = false);
        $this->count = 0;
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return json_encode($this);
    }
    public function addItem($item):void{
        $this->count++;
        $this->items[] = $item;
    }
    public function getCartId(): string {
        return $this->cartId;
    }
    public function getCount(): int
    {
        return $this->count;
    }

}