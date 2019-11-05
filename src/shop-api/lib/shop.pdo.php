<?php

// define('DBNAME', 'ekdata');
// define('DBHOST', 'localhost');
// define('DBUSER', 'ekdbuser');
// define('DBPASS', 'qewqewqew');

// define('PDO_DSN', 'mysql:host='.DBHOST.';dbname='.DBNAME.';port=3306;charset=utf8mb4');

// define('PDO_OPTS' ,array(
//     // PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
// ));
 

class ShopConnection
{
    const DBNAME = "shop";
    const DBHOST = "localhost";
    const DBUSER = "dagshopuser";
    const DBPASS = "do4ng9sb45ig0sb38glqmxb";

    const PDO_DSN = 'mysql:host='.self::DBHOST.';dbname='.self::DBNAME.';port=3306;charset=utf8mb4';

    const PDO_OPTS  = array(
        // PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    );

    public function __construct(){ }

    /**
     * ritorna una connessione del tipo PDO
     * @return PDO
     */
    public static function pdo(): PDO
    {
        try {
            $pdo = new  PDO(self::PDO_DSN, self::DBUSER, self::DBPASS, self::PDO_OPTS);
            return $pdo;
        } catch (\Exception $e) {
            throw new Exception("Errore iniziale di connessione al server con PDO - database ". self::DBNAME, 500);
            die($e);
        }
    }
}


?>
