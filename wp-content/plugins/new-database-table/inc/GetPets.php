<?php

class GetPets {
    private $placeholders;
    private $args;
    /**
     * @var array|object|stdClass[]|null
     */
    public $pets;

    public function __construct(){
        global $wpdb;
        $tableName = $wpdb->prefix . 'pets';
        //$ourQuery = $wpdb->prepare("SELECT * FROM $tableName WHERE species = %s ", [$_GET['species']??null]);


        // Grabs everything from the url and sanitizes it.
        $this->args = $this->getArgs();

        // Create a placeholder array
        $this->placeholders = $this->createPlaceholders();

        // Build the query: Iterate through args and add to WHERE query
        $limit = "100";
        $query = "Select * FROM $tableName ";
        $query .= $this->createQueryText();
        $query .= " LIMIT %d";

        $this->placeholders[] = $limit;


        $this->pets = $wpdb->get_results($wpdb->prepare($query,$this->placeholders));
    }
    function getArgs(): array
    {
        $temp = [
            'favcolor' => sanitize_text_field($_GET['favcolor'] ?? null),
            'species' => sanitize_text_field($_GET['species'] ?? null),
            'minyear' => sanitize_text_field($_GET['minyear'] ?? null),
            'maxyear' => sanitize_text_field($_GET['maxyear'] ?? null),
            'minweight' => sanitize_text_field($_GET['minweight'] ?? null),
            'maxweight' => sanitize_text_field($_GET['maxweight'] ?? null),
            'favhobby' => sanitize_text_field($_GET['favhobby'] ?? null),
            'favfood' => sanitize_text_field($_GET['favfood'] ?? null),
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
            case "minweight":
                return "petweight >= %d";
            case "maxweight":
                return "petweight <= %d";
            case "minyear":
                return "birthyear >= %d";
            case "maxyear":
                return "birthyear <= %d";
            default:
                return $index . "= %s";
        }
    }
}

