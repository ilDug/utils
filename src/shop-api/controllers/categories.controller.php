<?php
require_once __DIR__ . '/../lib/shop.pdo.php';
require_once __DIR__ . '/../lib/utils.php';



class CategoriesController 
{
    public  $pdo;
    
    // const QF_EDIT  = __DIR__ . "/../queries/product-edit.sql";
    
    const Q_SELECT  = "SELECT * FROM categories WHERE name LIKE :name";
    const Q_INSERT  = "INSERT INTO categories (name, title) VALUES (:name, :title)";
    const Q_EDIT  = "UPDATE categories SET name = :name, title = :title WHERE name = :old_name";
    const Q_DELETE  = "DELETE FROM categories WHERE name = :name";
    const Q_ASSIGN  = "INSERT INTO product_categories (productId, categoryId) VALUES (:productId, :cat_name)";
    const Q_DETACH  = "DELETE FROM product_categories WHERE productId = :productId AND categoryId = :cat_name";

    const FIELDS = [
        "name",
        "title"
    ];
    
    function __construct() { $this->pdo = ShopConnection::pdo(); }



    
    public function read($name = false)
    {
        $name = !$name ? "%" : $name;
        $st = $this->pdo->prepare(self::Q_SELECT);
        $st->bindParam(':name',    $name,     PDO::PARAM_STR);
        $st->execute();
        // echo $st->debugDumpParams();
        $categories = array();
        while($row = $st->fetch()){
            $categories[] = $row; 
        }
        return $name == "%" ? $categories : ($categories[0] ? $categories[0] : null);
    }





    /** add ITEM, return added item or NULL*/
    public function add($category)
    {
        if(!DAG\UTILS\checkFields($category, self::FIELDS)){ return false;}
        $st = $this->pdo->prepare(self::Q_INSERT);
        $st->bindParam(':name',    $category->name,    PDO::PARAM_STR);
        $st->bindParam(':title',   $category->title,   PDO::PARAM_STR);
        if(!$st->execute() )  
            throw new Exception("Errore inserimento " . json_encode($st->errorInfo()), 500); 
        else return $category;
    }





    /**
     * edit a category. return modified product or NULL
     */
    public function edit($category, $old_name)
    {
        if(!DAG\UTILS\checkFields($category, self::FIELDS)){ return false;}

        $st = $this->pdo->prepare(self::Q_EDIT);
        $st->bindParam(':old_name',    $old_name,          PDO::PARAM_STR);
        $st->bindParam(':name',        $category->name,    PDO::PARAM_STR);
        $st->bindParam(':title',       $category->title,   PDO::PARAM_STR);
        if(!$st->execute() || ($st->rowCount()) < 1) 
            throw new Exception("Errore modifica " . json_encode($st->errorInfo()), 500);

        else return $this->read($category->name);
    }



    /**
     * remove a product and return the number of removed items.
     */
    public function remove($cat_name)
    {
        $st = $this->pdo->prepare(self::Q_DELETE);
        $st->bindParam(':name',    $cat_name,    PDO::PARAM_STR);
        if(!$res = $st->execute() ) 
            throw new Exception("Errore eliminazione " . json_encode($st->errorInfo()), 500);
        else return $st->rowCount();
    }



    /**
     * assegna la categoria ad un prodotto; return BOOLEAN
     */
    public function assign($cat_name, $productId)
    {
        if(!$cat_name) {throw new Exception("Attibuto mancante NOME CATEGORIA" , 400); return false;}
        if(!$productId) {throw new Exception("Attibuto mancante ID PRODOTTO" , 400); return false;}

        $st = $this->pdo->prepare(self::Q_ASSIGN);
        $st->bindParam(':productId',    $productId,    PDO::PARAM_STR);
        $st->bindParam(':cat_name',     $cat_name,     PDO::PARAM_STR);
        if(!$res = $st->execute() ) 
            throw new Exception("Errore assegnazione categoria " . json_encode($st->errorInfo()), 500);
        else return $st->rowCount() > 0;
    }



    /** 
     * toglie un prodotto da una categoria; return BOOLEAN
     */
    public function detach($cat_name, $productId)
    {
        if(!$cat_name) {throw new Exception("Attibuto mancante NOME CATEGORIA" , 400); return false;}
        if(!$productId) {throw new Exception("Attibuto mancante ID PRODOTTO" , 400); return false;}
        
        $st = $this->pdo->prepare(self::Q_ASSIGN);
        $st->bindParam(':productId',    $productId,        PDO::PARAM_STR);
        $st->bindParam(':cat_name',     $cat_name,         PDO::PARAM_STR);
        if(!$res = $st->execute() ) 
            throw new Exception("Errore rimozione categoria da prodotto " . json_encode($st->errorInfo()), 500);
        else return $st->rowCount() > 0;    
    }




}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ?>