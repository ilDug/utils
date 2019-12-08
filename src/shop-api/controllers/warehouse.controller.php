<?php

class WareHouseController
{
    public $pdo;

    function __construct()
    {
        $this->pdo = ShopConnection::pdo();
    }


    public function addArticle($article)
    {
        // to do
    }
}