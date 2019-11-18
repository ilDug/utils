<?php
require_once __DIR__ . "/account.controller.php";

use \DAG\Mail;
use \DAG\Template;

class AccountActivationController extends AccountController
{

    const QF_RESEND_ACTIVATION_SEARCH = __DIR__ . "/../queries/users.resend_activation_select.sql";
    const Q_USER_SET_ACTIVE = "UPDATE users SET active = 1 WHERE uid = :uid";
    const Q_SET_ACTIVATION_DATE = "UPDATE activations SET activationDate = CURRENT_TIMESTAMP WHERE activationKey = :akey";


    public function __construct()
    {
        parent::__construct();
    }



    /**
     * cerca la chiave di attivazione
     * controlla che non sia già attivo
     * manda la mail
     * @return boolean se la mail è stata invata
     */
    public function resendActivationMail($email)
    {
        /** EMAIL */
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception("l'email non e' nella forma corretta", 400);

        $sql = file_get_contents(self::QF_RESEND_ACTIVATION_SEARCH);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(':email', $email, PDO::PARAM_STR);
        $st->bindValue(':scope', self::ACTIVATION_SCOPE, PDO::PARAM_STR);
        if (!$st->execute())  throw new Exception("impossibile recuperare il codice di attivazione", 500);
        if (!$data = $st->fetch())  throw new Exception("email non trovata, esegui prima la registrazione", 400);

        if ($data->active == 1) throw new Exception("utente gia' attivo", 400);


        /** crea una mail */
        $body = (new Template(self::EF_ACTIVATION_EMAIL))
                    ->fill(["%ACTIVATION_KEY%"], [$data->activationKey])
                    ->payload;

        try {
            $mail = new Mail();
            return $mail
                ->setSubject("Attivazione Account")
                ->setBody($body)
                ->addReceiver($email)
                ->send();
        } catch (\Exception $e) {
            throw $e;
            return;
        }
    }



    /**
     * attiva l'utente
     */
    public function activate($key)
    {
        $key = trim($key);
        if (strlen(strlen($key) != self::ACTIVATION_KEY_LENGTH)) throw new Exception("invalid activation link", 400);

        $sql = file_get_contents(self::QF_USER_ACTIVATION_DATA);
        $st = $this->pdo->prepare($sql);
        $st->bindParam(":key", $key, PDO::PARAM_STR);
        $st->execute();

        if (!$activation = $st->fetch()) {
            throw new Exception("chiave di attivazione inesistente", 400);
            return;
        }
        if ($activation->active == 1) {
            throw new Exception("utente gia' attivo", 400);
            return;
        }
        if ($activation->scope !== self::ACTIVATION_SCOPE) {
            throw new Exception("la chiave fornita non e' adatta per l'attivazione dell'account", 400);
            return;
        }

        try {
            $this->pdo->beginTransaction();

            $st = $this->pdo->prepare(self::Q_USER_SET_ACTIVE);
            $st->bindParam(':uid', $activation->uid, PDO::PARAM_STR);
            if (!$st->execute()) throw new Exception("errore attivazione account", 500);

            $st = $this->pdo->prepare(self::Q_SET_ACTIVATION_DATE);
            $st->bindParam(':akey', $activation->aKey, PDO::PARAM_STR);
            if (!$st->execute()) throw new Exception("errore attivazione account DATE ERROR", 500);

            $this->pdo->commit();
            return true;
        } catch (\Throwable $th) {
            $this->pdo->rollback();
            throw $th;
            return false;
        }
    }
}