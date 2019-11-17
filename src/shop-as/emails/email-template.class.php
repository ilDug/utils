<?php

namespace DAG;

class Template
{
    public $payload;

    public function __construct($template_abs_path)
    {
        $this->payload = file_get_contents($template_abs_path);
    }


    /**
     * sostituisce i placeholder con i valori passati come valore
     * @param $pairs_array un array di coppie di valori $placeholder => $value
     */
    public function compile($pairs_array)
    {
        $placeholders = array_keys($pairs_array);
        $values = array_values($pairs_array);
        $this->fill($placeholders, $values);
        return $this;
    }


    public function fill($placeholders, $values)
    {
        $placeholders = is_array($placeholders) ? $placeholders : array($placeholders);
        $values = is_array($values) ? $values : array($values);
        $this->payload = str_replace($placeholders, $values, $this->payload);
        return $this;
    }
}