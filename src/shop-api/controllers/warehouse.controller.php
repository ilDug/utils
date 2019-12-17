<?php
require_once __DIR__ . '/../lib/shop.pdo.php';
require_once __DIR__ . '/../lib/utils.php';
require_once __DIR__ . '/../lib/date.utility.php';

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

        $sql = file_get_contents(self::QF_INSERT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':productId', $article->productId, PDO::PARAM_STR);
        $st->bindParam(':sku', $article->sku, PDO::PARAM_STR);
        $st->bindParam(':batch', $article->batch, PDO::PARAM_STR);
        $st->bindParam(':item', $article->item, PDO::PARAM_INT);
        $st->bindParam(':dateExpiry', $dateExpiry, PDO::PARAM_STR);
        $st->bindParam(':status', $article->status, PDO::PARAM_STR);
        $res = $st->execute();
        if (!$res)
            throw new Exception("errore inserimento articolo in magazzino " . json_encode($st->errorInfo()[2]), 500);
        else return $this->read($this->pdo->lastInsertId());
    }
}
