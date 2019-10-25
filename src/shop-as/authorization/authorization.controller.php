<?php
require_once __DIR__ . '/../authentication/authentication.controller.php';
require_once __DIR__ . "/../lib/pdo-connection.php";

/**
 * classe creata per i controlli di autorizzazione e autenticazione
 * si istanzia con gli header di del protoccollo http
 *          $requestHeaders =  apache_request_headers();
 * da ottenere all'inizio del file;
 * ogni operazione deve contenere
 *          $auth = new Auth($requestHeaders['Authorization']);
 *          $userIDP = $auth->isAuthenticated ? $auth->claims['uid'] : null;
 */
class AuthorizationController extends AuthenticationController
{
    public $pdo;

    public function __construct() { 
        parent::__construct(); 
        $this->pdo = DagConnection::pdo();
    }





    
    /**
     * assegna il permission all'utente
     */
    public function assignAuthorization($permission, $uid){
        if(!isset($permission) || !isset($uid)) {throw new Exception("argomenti non validi", 400); return false;}
        if(!$this->authenticated) {throw new Exception("amministratore non autenticato", 401); return false;}

        if(!in_array( "assign_authorizations", $this->claims->authorizations )) {throw new Exception("amministratore non autorizzato", 401); return false;}
        
        $sql = file_get_contents(__DIR__ . "/../queries/user_assign_authorization.sql");
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':uid', $uid, PDO::PARAM_STR );
        $st->bindParam(':authorization', $permission, PDO::PARAM_STR );
        return $st->execute();
    }


    /**
     * assegna il permission all'utente
     */
    public function denyAuthorization($permission, $uid){
        if(!isset($permission) || !isset($uid)) {throw new Exception("argomenti non validi", 400); return false;}
        if(!$this->authenticated) {throw new Exception("amministratore non autenticato", 401); return false;}

        if(!in_array( "assign_authorizations", $this->claims->authorizations )) {throw new Exception("amministratore non autorizzato", 401); return false;}
        
        $sql = file_get_contents(__DIR__ . "/../queries/user_deny_authorization.sql");
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':uid', $uid, PDO::PARAM_STR );
        $st->bindParam(':authorization', $permission, PDO::PARAM_STR );
        return $st->execute();
    }




    public function authList(){
        foreach ($this->pdo->query("SELECT * FROM ekusers.authorizations") as $auth) {
            $list[$auth->authorization] = $auth->description;
        }
        return $list;
    }


}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ?>