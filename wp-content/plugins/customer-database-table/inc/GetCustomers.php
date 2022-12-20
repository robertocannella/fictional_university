<?php
/**
 * Queries a customers table
 *
 * PHP version 7.0
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Queries
 * @package    Customer Data
 * @author     Roberto Cannella <info@robertocannella.com>
 * @copyright  2022 Roberto Cannella
 * @license    Free
 * @version    0.0.1
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 0.0.1
 */


class GetCustomers {
    private $placeholders;
    private $args;
    /**
     * @var array|object|stdClass[]|null
     */
    public $customers;

    public function __construct(){
        global $wpdb;
        $tableName = $wpdb->prefix . 'customers';
        //$ourQuery = $wpdb->prepare("SELECT * FROM $tableName WHERE species = %s ", [$_GET['species']??null]);


        /*
         * Grabs everything from the url and sanitizes it
         */
        $this->args = $this->getArgs();
        /*
         * Create a placeholder array
         */
        $this->placeholders = $this->createPlaceholders();

        // Build the query: Iterate through args and add to WHERE query
        $limit = "100";
        $query = "Select * FROM $tableName ";
        $query .= $this->createQueryText();
        $query .= " LIMIT %d";

        $this->placeholders[] = $limit;


        $this->customers = $wpdb->get_results($wpdb->prepare($query,$this->placeholders));
    }
    function getArgs(): array
    {
        $temp = [
            'id' => sanitize_text_field($_GET['id'] ?? null),
            'name' => sanitize_text_field($_GET['fullname'] ?? null),
        ];

        return array_filter($temp, function ($x){
            return $x;
        });

    }
    function createPlaceholders(): array
    {
        return array_map(function($x){
            return $x;
        },$this->args);

    }

    function createQueryText(): string
    {
        $whereQuery = "";
        if (count($this->args)){
            $whereQuery = "WHERE ";
        }
        $current = 0;
        foreach ($this->args as $index => $item){
            $whereQuery .= $this->specificQuery($index);

            if ($current != count($this->args) - 1){
                $whereQuery .= " AND ";
            }
            $current++;
        }

        return $whereQuery;
    }
    function specificQuery($index): string
    {
        switch ($index){
            case "id":
                return "id = %d";
            case "fullname":
                return "name = %s";
            default:
                return $index . "= %s";
        }
    }
}