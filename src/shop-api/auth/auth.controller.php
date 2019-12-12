<?php

namespace DAG;

class Auth
{
    protected $auth_header;

    public $error;
    public $claims;

    const AUTH_SERVER_URL       = "https://shop.dag.lan/as/";
    const AUTH_SERVER_ENDPOINT  = "authentication/token/claims";
    const CA_PATH               = __DIR__ . "/certificates/";
    const CA_FILE               = self::CA_PATH . "dagCA.pem";


    public function __construct()
    {
        $this->auth_header = (apache_request_headers()['Authorization']);
        // echo $this->auth_header;
    }


    /**
     * autentica l'utente leggendo indipendentemente gli header della richiesta HTTP
     * @param boolean $throw indica se lanciare un exception in caso di fallimento dell'autenticazione
     * operativamente manda una richiesta GET via CURL al server di autenticazione ,  
     * aggiungere l'header AUTHORIZATION
     * aggiungere il percorso al certificato CA "pubblico"
     */
    public function authenticate($throw = false)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::AUTH_SERVER_URL . self::AUTH_SERVER_ENDPOINT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_CAPATH => self::CA_PATH,
            CURLOPT_CAINFO => self::CA_FILE,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $this->auth_header
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            die();
        } else {
            $x = json_decode($response);
            $this->error = (!$x->authenticated) ? $x->response : null;
            $this->claims = ($x->authenticated) ? $x->response : false;

            if ($throw && !$x->authenticated) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 401  ' . $this->error, true, 401);
                die();
            } else return $x->authenticated;
        }
    }


    /**
     * controlla se il token passato con il metodo authenticate 
     * possiede nella lista delle autorizzazioni il RUOLO passato come argomento
     * @param string $authorization l'autorizzazione da controllare
     * @param boolean $throw emette l'exception
     * @param boolean $die termina con die();
     */
    public function authorize($authorization, $throw = false, $errMessage = "Unauthorized", $die = false)
    {
        if (!in_array($authorization, $this->claims->authorizations)) {
            if ($throw) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 401 ' . $errMessage);
            }
            if ($die) die($errMessage);
            return false;
        } else return true;
    }
}//chiude la classe
