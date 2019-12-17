<?php

/** 
 * il codice rappresentativo di un singolo prodotto e poi più nello specifico di un articolo
 * nella forma: 
 *  PB.0000.1901.1.xxxx
 *  - PB = descrittore tipologia di prodotto (POSTERBOOK,  ABITI,  TECNOLOGIA) [lunghezza variabile tutto in maiuscolo]
 *  - 0000 = [quattro cifrein base 36] CODICE PRODOTTO 
 *  -- le prime due cifre sono per il BRAND: marca e modello [1296 combinazioni] 
 *  -- le ultime due cifre sono per la definizione del singolo articolo:taglia , materiale, colore, ecc  [1296 combinazioni]
 *  - 1901 = ARTICOLO: 19 è l'anno di acquisto o produzione [due cifre],  01 [due cifrein base 36] rappresenta il batch
 *  - 1 = il numero progressivo sequenziale che rappresenta il singolo item di un lotto o di una fornitura
 *  - xxxx = OPZIONALE serve per la tracciabilità in magazino [DA DEFINIRE]
 */
class SKU
{

    public function __construct($article)
    {
        $this->family = $article->family ? $article->family : null;
        $this->brand = $article->brand ? $article->brand : null;
        $this->element = $article->element ? $article->element : null;
        $this->year = $article->year ? $article->year : null;
        $this->batch = $article->batch ? $article->batch : null;
        $this->item = $article->item ? $article->item : null;
        $this->storage = $article->storage ? $article->storage : null;
    }

    /**descrittore tipologia di prodotto (POSTERBOOK,  ABITI,  TECNOLOGIA) [lunghezza variabile tutto in maiuscolo] */
    public $family;

    /**  due cifre - BRAND: marca e modello [1296 combinazioni] */
    public $brand;

    /** due cifre -   definizione del singolo articolo:taglia , materiale, colore, ecc  [1296 combinazioni] */
    public $element;

    /** anno di fornutura */
    public $year;

    /** numero di lotto */
    public $batch;

    /** numero progressivo sequenziale che rappresenta il singolo item di un lotto o di una fornitura */
    public $item;

    /** codice di archiviazione in magazzino */
    public $storage;

    /** codice del prodotto */
    public function sku()
    {
        return $this->family . '.' . $this->brand . $this->element;
    }

    /** codice della fornitura */
    public function supply()
    {
        return  $this->year . $this->batch;
    }

    /** codice completo del singolo item di una fornitura  */
    public function article()
    {
        return $this->supply . $this->item;
    }

    /** tutto il codice SKU */
    public function code()
    {
        return $this->productCode . $this->item;
    }
}


class Base36x2
{
    private $chars = array(
        "0",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "a",
        "b",
        "c",
        "d",
        "e",
        "f",
        "g",
        "h",
        "i",
        "j",
        "k",
        "l",
        "m",
        "n",
        "o",
        "p",
        "q",
        "r",
        "s",
        "t",
        "u",
        "v",
        "w",
        "x",
        "y",
        "z"
    );


    static public function next($prev)
    {
        $prev = (string) $prev;
        $len = strlen($prev);

        if ($len > 2) throw new \Exception("la lunghezza del codice è superiore a 2 carateri", 500);
        if ($prev == "zz") throw new \Exception("raggiunto il limite massimo", 500);

        $prev = $len == 2 ? $prev : "0" . $prev;
        $prev = str_split($prev);

        $i0 = array_search($prev[0], self::$chars);
        $i1 = array_search($prev[1], self::$chars);

        $next = array();
        $next[1] = $prev[1] == "z" ? "0" : self::$chars[((int) $i1 + 1)];
        $next[0] = $prev[1] == "z" ? self::$chars[((int) $i0 + 1)] : $prev[0];

        return $next[0] . $next[1];
    }
}
