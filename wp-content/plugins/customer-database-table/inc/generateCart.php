<?php

require_once plugin_dir_path(__FILE__) . '../utils/queryTools.php';
require_once plugin_dir_path(__FILE__) . 'Cart.php';
require_once plugin_dir_path(__FILE__) . 'generateCartItem.php';



function generateCart(): Cart{
    $cart = new Cart();
    global $wpdb;

    // Get the data
    $customerTableName = $wpdb->prefix . 'customers';
    $customerRows = $wpdb->get_results("SELECT id FROM $customerTableName");

    // Create arrays containing only valid IDs
    $customerIds = [];
    foreach ($customerRows as $entry) {
        $customerIds[] = $entry->id;
    }

    // Get a random id from the customer's table
    $cart->customerId = $customerIds[rand(0,count($customerRows)-1)];

    // How may items is the cart?
    $numCartItems = rand(1,5);

    for ($i = 0; $i < $numCartItems; $i++) {
        $cart->addItem(generateCartItem());
    }

    return $cart;

}

