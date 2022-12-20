<?php
require_once plugin_dir_path(__FILE__) . 'GetCustomers.php';
require_once plugin_dir_path(__FILE__) . 'GetOrders.php';
require_once plugin_dir_path(__FILE__) . 'generateOrders.php';
require_once plugin_dir_path(__FILE__) . 'generateCart.php';


$getCustomers = new GetCustomers();
$getOrders = new GetOrders();

$customers = [
    1 => [
        'name' => 'Alice',
        'orders' => [
            '49.99',
            '75.32',
        ]
    ]
];

$newArr = array_filter($customers, function($customer){
    return (array_sum($customer['orders']) > 100);
});


get_header(); ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">Customer Database</h1>
            <div class="page-banner__intro">
                <p>Search Customer Orders</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">

        <p>This page took <strong><?php echo timer_stop();?></strong> seconds to prepare. Found <strong>x</strong> results (showing the first x).</p>

        <table class="customer-table">
            <tr>
                <th>Name</th>
            </tr>
            <?php
            foreach($getCustomers->customers as $customer) { ?>
                <tr>
                    <td><?php echo $customer->name; ?></td>
                </tr>
            <?php }
            ?>
            <?php

            foreach($customers as $customer) {
                $orderSum = array_sum($customer['orders']);
                if ($orderSum >= 200){
                    ?>
                    <tr>
                        <td><?php echo $customer['name']; ?></td>
                        <td><?php echo $orderSum; ?></td>
                    </tr>
                    <?php
                }
                ?>


            <?php }
            ?>
        </table>

    </div>

<?php get_footer();