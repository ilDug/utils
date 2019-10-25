<?php

// define('DBNAME', 'ekusers');
// define('DBHOST', 'localhost');
// define('DBUSER', 'ekauthuser');
// define('DBPASS', '');

// define('PDO_DSN', 'mysql:host='.DBHOST.';dbname='.DBNAME.';port=3306;charset=utf8mb4');

// define('PDO_OPTS' ,array(
//     // PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
// ));
 

class AuthConnection
{
    const DBNAME = "ekusers";
    const DBHOST = "localhost";
    const DBUSER = "ekauthuser";
    const DBPASS = "";

    const PDO_DSN = 'mysql:host='.DBHOST.';dbname='.DBNAME.';port=3306;charset=utf8mb4';

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
            $pdo = new  PDO(PDO_DSN, DBUSER, DBPASS, PDO_OPTS);
            return $pdo;
        } catch (\Exception $e) {
            throw new Exception("Errore iniziale di connessione al server con PDO - database ". DB, 500);
            die($e);
        }
    }
}





?>
