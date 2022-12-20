<?php
require_once plugin_dir_path(__FILE__) . 'GetOrders.php';


$getOrders = new GetOrders();

get_header(); ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">Orders Database</h1>
            <div class="page-banner__intro">
                <p>Search Orders</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">

        <p>This page took <strong><?php echo timer_stop();?></strong> seconds to prepare. Found <strong>x</strong> results (showing the first x).</p>


        <table class="customer-table">
            <tr>
                <th>Order Id</th>
                <th>Cart Id</th>
                <th>Customer Id</th>
                <th>Product ID</th>
                <th>QTY</th>
                <th>Cost each</th>
                <th>Total cost</th>
            </tr>
            <?php
            foreach($getOrders->orders as $order) { ?>
                <tr>
                    <td><?php echo $order->id; ?></td>
                    <td><?php echo $order->cartId; ?></td>
                    <td><?php echo $order->customerId; ?></td>
                    <td><?php echo $order->productId; ?></td>
                    <td><?php echo $order->qty; ?></td>
                    <td><?php echo $order->cost; ?></td>
                    <td><?php echo $order->cost * $order->qty ; ?></td>

                </tr>
            <?php }
            ?>
            <?php

            foreach($getOrders as $order) {
                // $orderSum = array_sum($customer['orders']);
                //if ($orderSum >= 200){
                    ?>
                    <tr>

                    </tr>
                    <?php
                //}
                ?>


            <?php }
            ?>
        </table>

    </div>

<?php get_footer(); ?>