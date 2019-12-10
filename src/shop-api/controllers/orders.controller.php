<?php
require_once __DIR__ . '/../lib/shop.pdo.php';
require_once __DIR__ . '/../lib/utils.php';
require_once __DIR__ . '/../lib/date.utility.php';


class OrdersController
{
    public  $pdo;

    function __construct()
    {
        $this->pdo = ShopConnection::pdo();
    }

    const QF_INSERT = __DIR__ . '/../queries/order-insert.sql';
    const QF_UPDATE = __DIR__ . '/../queries/order-update.sql';


    const FIELDS = [
        "orderId",
        "uid"
    ];


    public function read()
    {
        /** TO DO fai riferimento a quello che c'è critto i products */
    }


    /** crea un ordine nel database */
    public function create($order)
    {
        if (!DAG\UTILS\checkFields($order, self::FIELDS)) {
            return false;
        }

        $order_json = json_encode($order);

        $sql = file_get_contents(self::QF_INSERT);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':orderId',    $order->orderId,    PDO::PARAM_STR);
        $st->bindParam(':uid',        $order->uid,        PDO::PARAM_STR);
        $st->bindParam(':order',      $order_json,        PDO::PARAM_STR);

        if (!$st->execute() || ($st->rowCount() < 1))
            throw new Exception("Errore inserimento " . json_encode($st->errorInfo()[2]), 500);
        else return $order;
    }

    /** modifica un ordine esistente */
    public function edit($order)
    {
        if (!DAG\UTILS\checkFields($order, self::FIELDS)) {
            return false;
        }

        $order_json = json_encode($order);
        $sql = file_get_contents(self::QF_UPDATE);

        $st = $this->pdo->prepare($sql);
        $st->bindParam(':orderId',    $order->orderId,    PDO::PARAM_STR);
        $st->bindParam(':uid',        $order->uid,        PDO::PARAM_STR);
        $st->bindParam(':order',      $order_json,        PDO::PARAM_STR);

        if (!$st->execute())
            throw new Exception("Errore modifica ordine " . json_encode($st->errorInfo()[2]), 500);
        else return $order;
    }
}
