<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../auth.config.php';
require_once __DIR__ . '/../engine.class.php';
require_once __DIR__ . '/../classes/jwt.php';
require_once __DIR__ . '/../classes/rsa-key-chain.php';


/**
 * classe creata per i controlli di autorizzazione e autenticazione
 * si istanzia con gli header di del protoccollo http
 *          $requestHeaders =  apache_request_headers();
 * da ottenere all'inizio del file;
 * ogni operazione deve contenere
 *          $auth = new Auth($requestHeaders['Authorization']);
 *          $userIDP = $auth->isAuthenticated ? $auth->claims['uid'] : null;
 */
class AuthenticationController
{
    public $authenticated; //controlla che JWT sia verificato
    public $claims = array(); //i dati contenuti nel token di autenticazione
    private $token;

    function __construct(){
        //inizializza i valori
        $this->authenticated = false;
    }




    
    /**
     * ritorna TRUE oppure il messaggio di errore
     */
    public function authenticate($auth_header){
        if(!$auth_header){
            return ("Unauthorized - l'header non contiene il valore  Authorization "); 
        } 
        
        $rawToken = str_replace('Bearer ', '', $auth_header);

        try { $this->token = new JWT($rawToken); }
        catch (\Exception $e) { 
            return ("Unauthorized - non e' stato possibile istanziare un token corretto  con la classe JWT"); 
        }

        if(!$this->token) { 
            return ("Unauthorized - non e' stato possibile istanziare un token corretto "); 
        }

        $this->claims = $this->token->payload;
        if ($this->token == null) {
            return ("Unauthorized - nessun token da autenticare "); 
        }

        $keys = new KeyChain();
        if(!$keys->public){
            return ("Unauthorized - chiave pubblica inesistente ");
        } 
        
        try {
            $this->authenticated = $this->token->check($keys->public);
        } catch (Exception $e) {
            return ("Unauthorized - errore nella verifica o validazine del token "); 
        }

        if(!$this->authenticated){ 
            return ("Unauthorized - il token non e' valido ");  
        }
        else return true;
    }


}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ?>