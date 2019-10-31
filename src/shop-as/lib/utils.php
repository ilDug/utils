<?php
namespace DAG\UTILS;

    /** controlla che l'oggetto contenga tutte le proprietà */
function checkFields($item, $fields)
{
    foreach ($fields as $field) {
        if(!$item->{$field}){ 
            throw new Exception("Attibuto mancante,  riprovare inserendo il valore per " . $field, 400);
            return false;
        } 
        else return true;
    }
}
