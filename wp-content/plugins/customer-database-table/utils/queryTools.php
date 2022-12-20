<?php

function createQueryText($args): string{
    $whereQuery = "";
    if (count($args)){
        $whereQuery = "WHERE ";
    }
    $current = 0;
    foreach ($args as $index => $item){
        $whereQuery .= specificQuery($index);

        if ($current != count($args) - 1){
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