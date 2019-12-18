<?php
require_once __DIR__ . '/../lib/shop.pdo.php';
require_once __DIR__ . '/../lib/utils.php';
require_once __DIR__ . '/../lib/date.utility.php';
require_once __DIR__ . '/../lib/Base36.php';

class WareHouseController
{
    public $pdo;

    function __construct()
    {
        $this->pdo = ShopConnection::pdo();
    }


    const FIELDS = [
        "productId",
        "sku",
        "batch",
        "item",
        "status"
    ];


    const QF_INSERT = __DIR__ . "/../queries/warehouse-article-insert.sql";
    const QF_SELECT = __DIR__ . "/../queries/warehouse-article-select.sql";
    const QF_UPDATE = __DIR__ . "/../queries/warehouse-article-update.sql";
    const Q_SELECT_SKU = "SELECT sku FROM products WHERE productId = productId;";
    // 


    public function read($articleId = false, $available = 0)
    {
        $articleId = !$articleId ? "%" : $articleId;
        $sql = file_get_contents(self::QF_SELECT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':articleid', $articleId, PDO::PARAM_STR);
        $st->bindParam(':available', $available, PDO::PARAM_INT);
        $st->execute();
        // echo $st->debugDumpParams();
        $articles = array();
        while ($row = $st->fetch()) {
            $row->dateIn = \DAG\UTILS\DateParser::mysqlToTimestampJS($row->dateIn);
            $row->dateOut = \DAG\UTILS\DateParser::mysqlToTimestampJS($row->dateOut);
            $row->dateExpiry = \DAG\UTILS\DateParser::mysqlToTimestampJS($row->dateExpiry);
            $articles[] = $row;
        }

        return $articleId == "%" ? $articles : ($articles[0] ? $articles[0] : null);
    }


    public function add_article($article)
    {
        if (!DAG\UTILS\checkFields($article, self::FIELDS)) {
            return false;
        }

        $dateExpiry = $article->dateExpiry ?  \DAG\UTILS\DateParser::timestampToMysql($article->dateExpiry) : null;
        $sku = $this->get_sku($article->productId);

        $sql = file_get_contents(self::QF_INSERT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':productId', $article->productId, PDO::PARAM_STR);
        $st->bindParam(':sku', $sku, PDO::PARAM_STR);
        $st->bindParam(':batch', $article->batch, PDO::PARAM_STR);
        $st->bindParam(':item', $article->item, PDO::PARAM_INT);
        $st->bindParam(':dateExpiry', $dateExpiry, PDO::PARAM_STR);
        $st->bindParam(':status', $article->status, PDO::PARAM_STR);
        $res = $st->execute();
        if (!$res)
            throw new Exception("errore inserimento articolo in magazzino " . json_encode($st->errorInfo()[2]), 500);
        else return $this->read($this->pdo->lastInsertId());
    }


    /**
     * STOCK
     * EXPIRED
     * SOLD
     * GIFT
     * OBSOLETE
     * LOST
     */
    public function set_article($articleId,  $status,  $dateOut =  false,  $available = 0)
    {
        $dateOut = $dateOut === false
            ? date("Y-m-d H:i:s")
            : ($dateOut === null
                ? null
                : \DAG\UTILS\DateParser::timestampToMysql($dateOut));

        $sql = file_get_contents(self::QF_SELECT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':articleid', $articleId, PDO::PARAM_INT);
        $st->bindParam(':available', $available, PDO::PARAM_INT);
        $st->bindParam(':status', $status, PDO::PARAM_STR);
        $st->bindParam(':dateOut', $dateOut, PDO::PARAM_STR);

        if (!$st->execute() || ($st->rowCount() < 1))
            throw new Exception("errore modifica dell\'articolo in magazzino " . json_encode($st->errorInfo()[2]), 500);
        else return $this->read($articleId);
    }





    /**
     * cerca la corrispondenza tra sku e productId nella tabella "products
     * altrimenti genera un nuovo sku
     */
    public function get_sku($productId)
    {
        $st = $this->pdo->prepare(self::Q_SELECT_SKU);
        $st->bindParam(':product:Id', $productId, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        return $row->sku;
    }


    public function next_batch()
    {
    }
}
