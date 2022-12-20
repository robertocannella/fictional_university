<?php

/*
*  Plugin Name: Customer Database
*  Version: 1.0
*  Author: Roberto Cannella
*  Author URI: https://www.robertocannella.com
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once plugin_dir_path(__FILE__) . 'inc/generateCustomer.php';
require_once plugin_dir_path(__FILE__) . 'inc/generateProduct.php';
require_once plugin_dir_path(__FILE__) . 'inc/generateCart.php';


/**
 * @property string $tablenameOrders
 */
class CustomerDatabasePlugin {
    function __construct() {
        global $wpdb;
        $this->charset = $wpdb->get_charset_collate();
        $this->tablenameCustomers = $wpdb->prefix . "customers";
        $this->tablenameOrders = $wpdb->prefix . "orders";
        $this->tablenameProducts = $wpdb->prefix . "products";

        add_action('activate_customer-database-table/customer-database-table.php', array($this, 'onActivate'));
        // will run on admin refresh
        add_action('admin_head', array($this, 'onAdminRefresh'));
        add_action('wp_enqueue_scripts', array($this, 'loadAssets'));
        add_filter('template_include', array($this, 'loadTemplate'), 99);
    }

    function onActivate() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Create a Customer Table
        dbDelta("CREATE TABLE $this->tablenameCustomers (
                  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                  name varchar(60) NOT NULL DEFAULT '',
                  PRIMARY KEY  (id)
                ) $this->charset;");

        // Create a Products table
        dbDelta("CREATE TABLE $this->tablenameProducts (
                  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                  name varchar(60) NOT NULL DEFAULT '',
                  cost decimal(10, 2) NOT NULL DEFAULT 10.00,
                  PRIMARY KEY  (id)
                ) $this->charset;");


        // Create an Orders Table
        dbDelta("CREATE TABLE $this->tablenameOrders (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                cartId varchar(50) NOT NULL,
                customerId smallint(5) NOT NULL DEFAULT 0,
                productId smallint(5) NOT NULL DEFAULT 0,
                qty smallint(5) NOT NULL DEFAULT 1,
                cost decimal(10, 2) NOT NULL,
                PRIMARY KEY  (id)
                ) $this->charset;");
    }

    function onAdminRefresh():string {
        global $wpdb;
        /*
         * Add a customer to the customer table
         */
        //$wpdb->insert($this->tablenameCustomers, generateCustomer());

        /*
         * Add a product to the product table
         */
        //$wpdb->insert($this->tablenameProducts, generateProduct());


        /*
         * Add a cart to the orders table
         */
        $cart = generateCart();
        for ($i = 0; $i < $cart->getCount() ; $i++) {
            $query = "INSERT INTO $this->tablenameOrders(`cartId`,`customerId`,`productId`,`qty`,`cost`) VALUES('{$cart->getCartId()}','{$cart->customerId}','{$cart->items[$i]['productId']}','{$cart->items[$i]['qty']}','{$cart->items[$i]['cost']}'); ";
            $wpdb->query($query);
        }

        /*
         * Proof the data was added
         */
        return $wpdb->insert_id;
    }

    function loadAssets() {
        if (is_page('customer-database') || is_page('order-database')) {
            wp_enqueue_style('customer-css', plugin_dir_url(__FILE__) . 'customer-database.css');
        }
    }

    function loadTemplate($template) {
        if (is_page('order-database')) {
            return plugin_dir_path(__FILE__) . 'inc/template-orders.php';
        }
        if (is_page('customer-database')) {
            return plugin_dir_path(__FILE__) . 'inc/template-customers.php';
        }
        return $template;
    }

    function populateFast() {
        $query = "INSERT INTO $this->tablename (`species`, `birthyear`, `petweight`, `favfood`, `favhobby`, `favcolor`, `petname`) VALUES ";
        $numberofpets = 100000;
        for ($i = 0; $i < $numberofpets; $i++) {
            $pet = generatePet();
            $query .= "('{$pet['species']}', {$pet['birthyear']}, {$pet['petweight']}, '{$pet['favfood']}', '{$pet['favhobby']}', '{$pet['favcolor']}', '{$pet['petname']}')";
            if ($i != $numberofpets - 1) {
                $query .= ", ";
            }
        }
        /*
        Never use query directly like this without using $wpdb->prepare in the
        real world. I'm only using it this way here because the values I'm
        inserting are coming from my innocent pet generator function; so I
        know they are not malicious, and I simply want this example script
        to execute as quickly as possible and not use too much memory.
        */
        global $wpdb;
        $wpdb->query($query);
    }

}

$customerDatabasePlugin = new CustomerDatabasePlugin();