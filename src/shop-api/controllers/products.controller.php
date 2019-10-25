<?php
require_once __RID__ . '/../queries/index.php';
require_once __RID__ . '/../lib/shop.pdo.php';



class ProductsController 
{
    public  $pdo;
    
    const QF_INSERT  = __DIR__ . "/../queries/product-insert.sql";
    const QF_EDIT  = __DIR__ . "/../queries/product-edit.sql";

    const Q_SELECT_ALL  = "SELECT * FROM products WHERE hidden = :hidden";
    const Q_SELECT_ONE  = "SELECT * FROM products WHERE productId = : productId AND hidden <= :hidden";
    const Q_DELETE  = "DELETE FROM products WHERE productId = :productId";
    const Q_HIDE  = "UPDATE products SET hidden = :hidden WHERE productId = :productId";

    const FIELDS = [
        "productId",
        "identifier",
        "sku",
        "brand",
        "product"
    ];
    
    function __construct() { $this->pdo = ShopConnection::pdo(); }



    /** controlla che l'oggetto contenga tutte le proprietà */
    private function checkFields($item, $fields)
    {
        foreach ($fields as $field) {
            if(!$item->{$field}){ 
                throw new Exception("Attibuto mancante,  riprovare inserendo il valore per " . $field, 400);
                return false;
            } 
            else return true;
        }
    }



    /** get whole list */
    public function read($hidden = 0)
    {
        $products = array();
        $st = $this->pdo->prepare(Q_SELECT_ALL);
        $st->bindParam(':hidden', $hidden, PDO::PARAM_INT);
        $st->execute();
        while($row = $st->fetch()){
            $products[] = json_decode(($row->product)); 
        }
        return $products;
    }



    public function findOne($productId , $hidden = 0)
    {
        $st = $this->pdo->prepare(Q_SELECT_ONE);
        $st->bindParam(':productId',    $productId,     PDO::PARAM_STR);
        $st->bindParam(':hidden',       $hidden,        PDO::PARAM_INT);
        $st->execute();
        $prod = $st->fetch();
        return json_decode($prod); 
    }



    /** add ITEM, return added item or NULL*/
    public function add($product)
    {
        if(!$this->checkFields($product, FIELDS)){ return false;}

        $product_json = json_encode($product);
        $sql = file_get_contents(QF_INSERT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':productId',    $product->productId,    PDO::PARAM_STR);
        $st->bindParam(':identifier',   $product->identifier,   PDO::PARAM_STR);
        $st->bindParam(':sku',          $product->sku,          PDO::PARAM_STR);
        $st->bindParam(':brand',        $product->brand,        PDO::PARAM_STR);
        $st->bindParam(':product',      $product_json,          PDO::PARAM_STR);
        if(!$res = $st->execute() ) 
        {
            throw new Exception("Errore inserimento " . json_encode($st->errorInfo()), 1);
            return NULL;
        } 
        else return $product;
    }





    /**
     * edit a product. return modified product or NULL
     */
    public function edit($product)
    {
        if(!$this->checkFields($product, FIELDS)){ return false;}

        $product_json = json_encode($product);

        $st = $this->pdo->prepare(Q_EDIT);
        $st->bindParam(':productId',    $product->productId,    PDO::PARAM_STR);
        $st->bindParam(':identifier',   $product->identifier,   PDO::PARAM_STR);
        $st->bindParam(':sku',          $product->sku,          PDO::PARAM_STR);
        $st->bindParam(':brand',        $product->brand,        PDO::PARAM_STR);
        $st->bindParam(':product',      $product_json,          PDO::PARAM_STR);
        if(!$res = $st->execute() ) 
        {
            throw new Exception("Errore modifica " . json_encode($st->errorInfo()), 1);
            return NULL;
        } 
        else return $product;
    }



    /**
     * remove a product and return the number of removed items.
     */
    public function remove($productId)
    {
        $st = $this->pdo->prepare(Q_DELETE);
        $st->bindParam(':productId',    $product->productId,    PDO::PARAM_STR);
        if(!$res = $st->execute() ) 
        {
            throw new Exception("Errore eliminazione " . json_encode($st->errorInfo()), 1);
            return false;
        } 
        else return $st->rowCount();
    }



    /** nasconde un prodotto dal catalogo; return BOOLEAN*/
    public function hide($productId, $hidden = 1){
        $st = $this->pdo->prepare(Q_HIDE);
        $st->bindParam(':productId',    $product->productId,    PDO::PARAM_STR);
        $st->bindParam(':hidden',       $hidden,                PDO::PARAM_INT);
        if(!$res = $st->execute() ) 
        {
            throw new Exception("Errore oscuramento " . json_encode($st->errorInfo()), 1);
            return false;
        } 
        else return $res;
    }




    /** rende visibile un prodotto nel catalogo, return BOOLEAN */
    public function show($productId){
        return $this->hide($productId, 0);
    }




}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ?>