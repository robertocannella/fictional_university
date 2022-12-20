<?php


function generateCartItem(): array{
    global $wpdb;

    /*
     * Retrieve the data.
     */
    $productTableName = $wpdb->prefix . 'products';
    // store a list of all the current ids in the products table
    $productIds = [];
    $productRows = $wpdb->get_results("SELECT id FROM $productTableName");
    foreach ($productRows as $entry) {
        $productIds[] = $entry->id;
    }

    /*
     * Build the cart item
     */
    // Get a random product from the product's table
    $currentProductId = $productIds[rand(0,count($productRows)-1)];
    $currentProduct = $wpdb->get_results("SELECT * FROM $productTableName WHERE id = $currentProductId");
    $currentProduct = $currentProduct[0];
    // Set a quantity for this cart item
    $qty = rand(1,4);

    return [
        'productId' => $currentProductId,
        'cost' => $currentProduct->cost,
        'qty' => $qty
    ];
}
