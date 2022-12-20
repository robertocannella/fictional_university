<?php

require_once plugin_dir_path(__FILE__) . '../utils/queryTools.php';

function generateOrders(): array{
    global $wpdb;

    // Get the data
    $customerTableName = $wpdb->prefix . 'customers';
    $productTableName = $wpdb->prefix . 'products';
    $orderTableName = $wpdb->prefix . 'orders';
    $customerRows = $wpdb->get_results("SELECT id FROM $customerTableName");
    $productRows = $wpdb->get_results("SELECT id FROM $productTableName");



    // Get a random id from the customer's table
    $numCustomers = count($customerRows);
    $currentCustomer = rand(1,count($customerRows));


    echo "<br>There are {$numCustomers} entries in the customer table</br>";
    //echo "<br>I chose customer {$currentCustomer} for this order</br>";

    // How may items is the cart?
    $numCartItems = rand(1,5);
    //echo "<br>I picked {$numCartItems} cart itmes for this customer</br>";

    ?>
    <table class="customer-table">
        <caption>Order for customer number <?php echo $currentCustomer ?> </caption>
        <tr>
            <th>Cart Item</th>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Name</th>
            <th>Cost Each</th>
            <th>Total Cost</th>
        </tr>
    <?php
    // For each item in the cart, let's generate a random quantity and calculate the cost
    $orders = [];
    $productIds = [];
    foreach ($productRows as $entry) {
        $productIds[] = $entry->id;
    }
    for ($i = 0; $i < $numCartItems; $i++) {
        echo "<tr>";


        // Get a random id from the product's table
        $qty = rand(1,4);
        $currentProduct = $productIds[rand(0,count($productRows)-1)];


        echo "<td>{$i}</td><td>{$currentProduct}</td><td>{$qty}</td>";

        $productQuery = "SELECT * FROM $productTableName ";
        $productQuery .= createQueryText([
                "id" => $currentProduct
        ]);

        $product = $wpdb->get_results($wpdb->prepare($productQuery,[$currentProduct]));

        echo "<td>{$product[0]->name}</td>";
        echo "<td>{$product[0]->cost}</td>";

        $totalCost = round($product[0]->cost * $qty,2);

        echo  "<td class='total-cost'>". number_format($totalCost, 2) . "</td>";

        echo "</tr>";
    }
    echo "</table>";



    // Get a random product

    // How may items is the cart?

    // How may of each item?



    //


    return $orders;

}
require_once plugin_dir_path(__FILE__) . '../utils/randomFloat.php';

