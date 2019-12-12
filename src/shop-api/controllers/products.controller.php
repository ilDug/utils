<?php
require_once __DIR__ . '/../lib/shop.pdo.php';
require_once __DIR__ . '/../lib/utils.php';



class ProductsController
{
    public  $pdo;

    const QF_INSERT  = __DIR__ . "/../queries/product-insert.sql";
    const QF_EDIT  = __DIR__ . "/../queries/product-edit.sql";
    const QF_SELECT  = __DIR__ . "/../queries/products-select.sql";
    const Q_DELETE  = "DELETE FROM products WHERE productId = :productId";
    const Q_HIDE  = "UPDATE products SET hidden = :hidden WHERE productId = :productId";

    const FIELDS = [
        "productId",
        "identifier",
        "sku",
        "brand",
        "product"
    ];

    function __construct()
    {
        $this->pdo = ShopConnection::pdo();
    }


    public function read($productId = false, $hidden = 0)
    {
        $productId = !$productId ? "%" : $productId;

        $sql = file_get_contents(self::QF_SELECT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':productId',    $productId,     PDO::PARAM_STR);
        $st->bindParam(':hidden',       $hidden,        PDO::PARAM_INT);
        $st->execute();
        // return $st->debugDumpParams();

        $products = array();
        while ($row = $st->fetch()) {
            $products[] = json_decode(($row->product));
        }

        return $productId == "%" ? $products : ($products[0] ? $products[0] : null);
    }



    /** add ITEM, return added item or NULL*/
    public function add($product)
    {
        if (!DAG\UTILS\checkFields($product, self::FIELDS)) {
            return false;
        }

        $product_json = json_encode($product);
        $sql = file_get_contents(self::QF_INSERT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':productId',    $product->productId,    PDO::PARAM_STR);
        $st->bindParam(':identifier',   $product->identifier,   PDO::PARAM_STR);
        $st->bindParam(':sku',          $product->sku,          PDO::PARAM_STR);
        $st->bindParam(':brand',        $product->brand,        PDO::PARAM_STR);
        $st->bindParam(':product',      $product_json,          PDO::PARAM_STR);
        if (!$res = $st->execute())
            throw new Exception("Errore inserimento " . json_encode($st->errorInfo()[2]), 500);
        else return $product;
    }





    /**
     * edit a product. return modified product or NULL
     */
    public function edit($product)
    {
        if (!DAG\UTILS\checkFields($product, self::FIELDS)) {
            return false;
        }

        $product_json = json_encode($product);
        $sql = file_get_contents(self::QF_EDIT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':productId',    $product->productId,    PDO::PARAM_STR);
        $st->bindParam(':identifier',   $product->identifier,   PDO::PARAM_STR);
        $st->bindParam(':sku',          $product->sku,          PDO::PARAM_STR);
        $st->bindParam(':brand',        $product->brand,        PDO::PARAM_STR);
        $st->bindParam(':product',      $product_json,          PDO::PARAM_STR);
        if (!$res = $st->execute())
            throw new Exception("Errore modifica " . json_encode($st->errorInfo()[2]), 500);
        else return $product;
    }



    /**
     * remove a product and return the number of removed items.
     */
    public function remove($productId)
    {
        $st = $this->pdo->prepare(self::Q_DELETE);
        $st->bindParam(':productId',    $productId,    PDO::PARAM_STR);
        if (!$res = $st->execute())
            throw new Exception("Errore eliminazione " . json_encode($st->errorInfo()[2]), 500);
        else return $st->rowCount();
    }



    /** nasconde un prodotto dal catalogo; return BOOLEAN*/
    public function hide($productId, $hidden = 1)
    {
        $st = $this->pdo->prepare(self::Q_HIDE);
        $st->bindParam(':productId',    $productId,     PDO::PARAM_STR);
        $st->bindParam(':hidden',       $hidden,        PDO::PARAM_INT);
        if (!$res = $st->execute())
            throw new Exception("Errore oscuramento " . json_encode($st->errorInfo()[2]), 500);
        else return $res;
    }




    /** rende visibile un prodotto nel catalogo, return BOOLEAN */
    public function show($productId)
    {
        return $this->hide($productId, 0);
    }
}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
