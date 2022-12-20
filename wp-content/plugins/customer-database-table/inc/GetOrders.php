<?php

/**
 * Queries an orders table.
 *
 * PHP version 7.1
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Queries
 * @package    Orders Data
 * @author     Roberto Cannella <info@robertocannella.com>
 * @copyright  2022 Roberto Cannella
 * @license    Free
 * @version    0.0.1
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 0.0.1
 */

class GetOrders{
    private $args;
    /**
     * @var array|object|stdClass[]|null
     */
    public $orders;

    public function __construct(){
        /*
         * Configure the WordPress DB Tool
         */
        global $wpdb;
        $tableName = $wpdb->prefix . 'orders';

        /*
         * Grabs everything from the url and sanitizes it
         */
        $this->args = $this->getArgs();

        /*
         * Build the query: Iterate through args and add to WHERE query
         */
        $limit = "100";
        $query = "Select * FROM $tableName ";
        $query .= $this->createQueryText();
        $query .= " LIMIT %d";

        $this->args[] = $limit;
        echo $query;
        $this->orders = $wpdb->get_results($wpdb->prepare($query,$this->args));

    }

    /**
     * Get the URL params.  Specify parameters specific to this query object
     * @return array
     */
    function getArgs(): array
    {
        $temp = [
            'id' => sanitize_text_field($_GET['id'] ?? null),
            'name' => sanitize_text_field($_GET['orderName'] ?? null),
            'cartId' => sanitize_text_field($_GET['cartId'] ?? null),
            'productId' => sanitize_text_field($_GET['productId'] ?? null),
            'qty' => sanitize_text_field($_GET['quantity'] ?? null),
            'cost' => sanitize_text_field($_GET['costEach'] ?? null),
            'minCost' => sanitize_text_field($_GET['minCost'] ?? null),
            'maxCost' => sanitize_text_field($_GET['maxCost'] ?? null),
        ];

        /*
         * Clear out all null values
         */
        return array_filter($temp, function ($x){
            return $x;
        });

    }

    /**
     * Builds the query string for WordPress prepare function
     * @return string
     *
     */
    function createQueryText(): string
    {

        $whereQuery = "";
        /*
         * Only if there are arguments
         */
        if (count($this->args)){
            $whereQuery = "WHERE ";
        }

        $current = 0;
        var_dump( $this->args);
        foreach ($this->args as $index => $item){
            $whereQuery .= $this->specificQuery($index);

            if ($current != count($this->args) - 1){
                $whereQuery .= " AND ";
            }
            $current++;
        }

        return $whereQuery;
    }

    /**
     * Builds the specific query for is class
     * @param $index
     * @return string
     */
    function specificQuery($index): string
    {
        switch ($index){
            case "id":
                return "id = %d";
            case "qty":
                return "qty = %d";
            case 'cost':
                return "cost = %f";
            case 'minCost':
                return "cost >= %f";
            case 'maxCost':
                return "cost <= %f";
            case "cartId":
                return "cartId = %s";
            // Everything else is a string
            default:
                return $index . "= %s";
        }
    }

}