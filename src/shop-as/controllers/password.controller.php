<?php

require_once __DIR__ . '/account.controller.php';

/*
* classe per la gestione degli accessi e degli utenti
* collegata ad una tabella del database che gestisce gli utenti
* IDPcode dato dalla forma criptata di mail+timestamp
*/
class PasswordController extends AccountController 
{

    function __construct() { parent::__construct(); }
    
    const Q_SELECT_RECOVER_EMAIL = "SELECT uid from users WHERE email = :email";
    const RESTORE_KEY_LENGTH = 64;
    const RECOVER_SCOPE = "recover_password";
    const Q_RECOVER_KEY_CREATE = "INSERT INTO activations (uid, activationKey, scope) VALUES ( :uid, :recoverKey, :scope )";

    const EF_RECOVER_EMAIL = __DIR__ . "/../emails/recover-password-email.html";
    /**
     * RECOVER PASSWORD
     * genera una chiave di attivazione che permette di ripristinare la password
     */
    public function recover($email)
    {
        /** EMAIL */
        if(!$email){throw new Exception("l'email assente", 400); return;}
        $email = strtolower(trim($email));
        if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) { throw new Exception("l'email non e' nella forma corretta", 400); return; }

        $st = $this->pdo->prepare(self::Q_SELECT_RECOVER_EMAIL);
        $st->bindParam(':email', $email, PDO::PARAM_STR);
        $st->execute();
        if(!$uid = $st->fetch()->uid) {throw new Exception("indirizzo email non presente nel database", 400); return;}
        
        $recoverKey =  StringTool::getRandomString(self::RESTORE_KEY_LENGTH);
        $st = $this->pdo->prepare(self::Q_RECOVER_KEY_CREATE);
        $st->bindParam(':uid',          $uid,                   PDO::PARAM_STR);
        $st->bindParam(':recoverKey',   $recoverKey,            PDO::PARAM_STR);
        $st->bindParam(':scope',        self::RECOVER_SCOPE,    PDO::PARAM_STR);
        if(!$st->execute()) {throw new Exception("Errore creazione chiave di recupero", 500); return;};

        /** crea una mail */
        $body = file_get_contents(self::EF_RECOVER_EMAIL);
        $body = str_replace([ "%RECOVER_KEY%" ], [ $recoverKey ], $body);

        try {
            $message = new DagMail("hello");
            return $message
                ->setSubject("Recupera la password")
                ->setBody($body)
                ->addRecipient($email)
                ->send();
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }





    /**
     * RESTORE PASSWORD
     * esegue i controlli per la reimpostazione della password utente
     * @return uid
     */
    public function initRestore($key)
    {
        // $key = trim($key);
        // if (strlen(strlen($key) != 64)) throw new Exception("invalid recover link", 400);

        // $st = $this->pdo->prepare(Q_USER_RESTORE_DATA);
        // $st->bindParam(':key', $key, PDO::PARAM_STR);
        // $st->execute();
        // $d = $st->fetch();

        // if( $st->rowCount() == 0) {throw new Exception("chiave di recupero inesistente", 400); return;}
        // if( $d->scope !== "recover_password" ) {throw new Exception("la chiave fornita non e' adatta per il recupero della password", 400); return;}
        // if( $d->activationDate ) {throw new Exception("la chiave fornita e' gia' stata utilizzata", 400); return;}
        // if( DateParser::mysqlToTimestampJs($d->generationKeyDate) + 1000 * 60 * 60 < time()*1000  ) {throw new Exception("la chiave fornita e' scaduta", 400); return;}
        // return $d->uid ? $d->uid : false;
    }



    /**
     * RESTORE PASSWORD
     * imposta la nuova password
     * @return bool
     */
    public function restore($key, $newpassword)
    {
        // /** esegue di nuovo i controlli per la chiave */
        // $uid = $this->initRestorePassword($key);
        // if( $uid  == false )return ;

        // $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

        // $st = $this->pdo->prepare("UPDATE users SET password = :password WHERE uid = :uid");
        // $st->bindParam(':password', $newpassword, PDO::PARAM_STR);
        // $st->bindParam(':uid', $uid, PDO::PARAM_STR);
        // if (!$st->execute() || $st->rowCount() < 1) return false;

        // $st = $this->pdo->prepare("UPDATE activations SET activationDate = CURRENT_TIMESTAMP WHERE activationKey = :key");
        // $st->bindParam(':key', $key, PDO::PARAM_STR);
        // if (!$st->execute() || $st->rowCount() < 1 ) return false;
        // return true;
    }

}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ?>