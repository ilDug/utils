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
        "sku"
    ];


    const QF_INSERT = __DIR__ . "/../queries/warehouse-article-insert.sql";
    const QF_SELECT = __DIR__ . "/../queries/warehouse-article-select.sql";
    const QF_UPDATE = __DIR__ . "/../queries/warehouse-article-update.sql";




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


    public function add_articles($article, $quantity = 1)
    {
        if (!DAG\UTILS\checkFields($article, self::FIELDS)) return false;
        $article->dateExpiry = $article->dateExpiry ?  \DAG\UTILS\DateParser::timestampToMysql($article->dateExpiry) : null;
        $article->batch = $this->next_batch($article->sku);

        try {
            $this->pdo->beginTransaction();
            $articles = array();

            for ($i = 0; $i < $quantity; $i++) {
                $sql = file_get_contents(self::QF_INSERT);
                $st = $this->pdo->prepare($sql);
                $st->bindParam(':productId', $article->productId, PDO::PARAM_STR);
                $st->bindParam(':sku', $article->sku, PDO::PARAM_STR);
                $st->bindParam(':batch', $article->batch, PDO::PARAM_STR);
                $st->bindParam(':item', $quantity + 1, PDO::PARAM_INT);
                $st->bindParam(':dateExpiry', $article->dateExpiry, PDO::PARAM_STR);
                $st->bindParam(':status', $article->status, PDO::PARAM_STR);
                $res = $st->execute();
                if (!$res)
                    throw new Exception("errore inserimento articolo in magazzino " . json_encode($st->errorInfo()[2]), 500);
                else
                    $articles[] = $this->read($this->pdo->lastInsertId());
            }

            $this->pdo->commit();
            return $articles;
        } catch (\Throwable $th) {
            $this->pdo->rollback();
            throw $th;
            return;
        }
    }


    /**
     * STOCK
     * EXPIRED
     * SOLD
     * GIFT
     * OBSOLETE
     * LOST
     * 
     * modifica un article nel warehouse
     * può modificare solo available,  lo status e la data di uscita
     * @param string status 
     * @param int articleId
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
     * batch nella forma 19xx
     * @return string quattro caratteri
     */
    private function next_batch($sku)
    {
        // cerca l'ultimo batch di un sku
        $sql = "SELECT  batch from warehouse WHERE sku = :sku ORDER BY batch DESC LIMIT 1 ";
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':sku', $sku, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();

        $year = date('y');

        /** se non esiste il batch lo crea nuovo */
        if (!$row) return $year . Base36x2::next('');

        /** se esiste il batch divide l'anno e il codice */
        $y = substr($row->batch, 0, 2);
        $b = substr($row->batch, 2, 2);

        /** controllo anno. se l'anno in corso è maggiore dell'ultimo batch allora ricomincia da capo */
        if ($year > $y) return $year . Base36x2::next('');
        else return $y . Base36x2::next($b);
    }
}