<?php
require_once __DIR__ . '/../app.config.php';
require_once __DIR__ . '/../lib/as.pdo.php';
require_once __DIR__ . '/../lib/utils.php';
require_once __DIR__ . '/../lib/string.utility.php';
require_once __DIR__ . '/../classes/jwt.php';
require_once __DIR__ . '/../classes/rsa-key-chain.php';
require_once __DIR__ . '/../emails/email.class.php';


	/*
	* classe per la gestione degli accessi e degli utenti
	* collegata ad una tabella del database che gestisce gli utenti
	* IDPcode dato dalla forma criptata di mail+timestamp
	*/
	class AccountController
	{
        const Q_USER_TOKEN_DATA = "SELECT * from users_token_data WHERE uid = :uid;";
        const Q_ACCESS_INSERT = "INSERT INTO accesses (uid, jti, ip) VALUES (:uid, :jti, :ip)";
        const QF_USERS_INSERT = __DIR__ . "/../queries/users.insert.sql";
        const Q_USER_ASSIGN_AUTHORIZATION = "INSERT INTO user_authorizations (uid, authorization) VALUES (:uid, :authorization)";
        const Q_ACTIVATION_KEY_INSERT = "INSERT INTO activations (uid, activationKey, scope) VALUES ( :uid, :activation_key, :scope )";
        const QF_USER_ACTIVATION_KEY = __DIR__ . "/../queries/users_select_activation_key.sql";
        const QF_USER_ACTIVATION_DATA = __DIR__ . "/../queries/users_select_activation_data.sql";
        
        const EF_ACTIVATION_EMAIL = __DIR__ . "/../emails/user-activation-email.html";
        const ACTIVATION_KEY_LENGTH = 64;
        const ACTIVATION_SCOPE = "account_activation";

        public  $pdo ;
        function __construct() { $this->pdo = AuthConnection::pdo(); }

		/**
		*	ricerca nel database la corrispondenza tra email e password
		*	resitutisce il token
		*/
		public function login($email, $pw)
		{
            /** controlla che i parametri esistano */
            if(!$email){ throw new Exception("Email assente", 400); return false; }
            if(!$pw){ throw new Exception("Password assente", 400); return false; }

            /** prova a recuperare i dati dell'utente con la sola email */
            $st = $this->pdo->prepare("SELECT password, active, uid from users WHERE email = :email");
            $st->bindParam(':email', $email, PDO::PARAM_STR );
            $st->execute();
            $o = $st->fetch();
            
			//gestisce l'errore se non esiste l'utente nel database
			if(!$o){
				throw new Exception("Utente non registrato. Procedi prima con la registrazione del tuo account.", 500);
				return false;
			}else{
				if($o->active == 0){
					// throw new Exception( " L'utente  non e' ancora stato attivato. Procedere con l'attivazione dell'account . Se ci sono problemi contatta il supporto tecnico", 500);
					// return false;
				}
                if(!password_verify($pw, $o->password)){
                    throw new Exception("password non corretta per questo account.", 500);
                    return false;
                }else{
                    /** ottiene i dati da inserire nel token */
                    $st = $this->pdo->prepare(self::Q_USER_TOKEN_DATA);
                    $st->bindParam(':uid', $o->uid, PDO::PARAM_STR );
                    $st->execute();
                    if (!$u = $st->fetch()) { throw new Exception("Errore ricerca utente", 500); return false; }

                    $rawClaims = array(
                        "iss" => JWT_ISS,
                        "aud" => JWT_AUD,
                        "sub" => $u->uid,
                        "uid" => $u->uid,
                        "iat" => null,
                        "nbf" => null,
                        "duration" => null,
                        "exp" => null,
                        "jti" => null,
                        "email"=> $u->email, 
                        "active" => $u->active,
                        "authorizations" => json_decode($u->authorizations)
                    );

                    $keys = new KeyChain();
                    $jwt = (new JWT());
                    $token = $jwt->generate($rawClaims, $keys->private);
                    $jti = $jwt->payload->jti; 
                    $ip = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : NULL;

                    $st = $this->pdo->prepare(self::Q_ACCESS_INSERT);
                    $st->bindParam(':uid',  $u->uid,  PDO::PARAM_STR );
                    $st->bindParam(':jti',  $jti,   PDO::PARAM_STR );
                    $st->bindParam(':ip',   $ip,    PDO::PARAM_STR );
                    $st->execute();
                    
                    if($st->rowCount() <= 0) {
                        throw new Exception("Errore,  si sono verificati problemi con la connsessione al server, riprovare più tardi", 500);
                        return false;
                    }else{
                        return $token ;
                    }
                }
			}		
        }
        



        /**
         *  registra un nuovo utente +
        */
        public function register($user)
        {
            if(!$user) { throw new Exception("parametro UTENTE non corretto", 400); return;   }

            /** cotrolla che non manchino i parametri nell'oggetto USER */
            if(!DAG\UTILS\checkFields($user, ["email", "password"])) return false;

            /** EMAIL */
            $user->email = strtolower(trim($user->email));
            if( !filter_var($user->email, FILTER_VALIDATE_EMAIL) ) {
                throw new Exception("l'email non e' nella forma corretta", 400);
                return;  
            }

            /** controlla che l'utente esista */
            if($this->user_exists($user->email)){
                throw new Exception("un utente con questa email esiste gia' nel database", 400);
                return;             
            }
            
            $original_password  =   $user->password ;
            $user->password     =   password_hash( $user->password, PASSWORD_DEFAULT);
            $user->uid          =   md5( $user->email . microtime());
            // $user->username     =   trim(           (strtolower($user->username)) ) ;
            // $user->firstName    =   trim( ucfirst(  (strtolower($user->firstName)) ) ) ; 
            // $user->familyName   =   trim( ucfirst(  (strtolower($user->familyName)) ) ) ; 


            do {
                $activation_key = StringTool::getRandomString(self::ACTIVATION_KEY_LENGTH);
                $st = $this->pdo->query("SELECT activationKey FROM activations WHERE activationKey = '$activation_key'");
            } while ($st->rowCount() > 0);


           
            try {
                // First of all, let's begin a transaction
                $this->pdo->beginTransaction();

                // inserisce il nuovo utente
                $sql =  file_get_contents(self::QF_USERS_INSERT);
                $st = $this->pdo->prepare($sql);
                $st->bindParam(':uid', $user->uid, PDO::PARAM_STR );
                $st->bindParam(':email', $user->email, PDO::PARAM_STR );
                $st->bindParam(':password', $user->password, PDO::PARAM_STR );
                if(!$st->execute()) throw new Exception("Errore inserimento utente", 500);
                
                /** attribuise le autorizzazioni di base */
                $st = $this->pdo->prepare(self::Q_USER_ASSIGN_AUTHORIZATION);
                $st->bindParam(':uid', $user->uid, PDO::PARAM_STR);
                $st->bindValue(':authorization', "basic", PDO::PARAM_STR);
                if (!$st->execute()) throw new Exception("Errore assegnazione autorizzazione di base all'utente", 500);
                
                
                /* genera una chiave di attivazione e la inserisce */
                $st = $this->pdo->prepare(self::Q_ACTIVATION_KEY_INSERT);
                $st->bindParam(':uid',              $user->uid,             PDO::PARAM_STR);
                $st->bindParam(':activation_key',   $activation_key,        PDO::PARAM_STR);
                $st->bindValue(':scope',            self::ACTIVATION_SCOPE,  PDO::PARAM_STR);
                if (!$st->execute()) throw new Exception("Errore generazione chiave di attivazione", 500);
                
                $this->sendActivationMail($user->uid);
                
                // If we arrive here, it means that no exception was thrown
                $this->pdo->commit();
                $token = $this->login($user->email, $original_password);
                return $token;

            } catch (Exception $e) {
                $this->pdo->rollback();
                throw $e;
                return;
            }
        }



        /** 
         * controlla se un utente è gia stato registrato
         * controlla username e email
         * @return boolean
         */
        private function user_exists($email)
        {
            $st = $this->pdo->prepare("SELECT uid from users WHERE email = :email");
            $st->bindParam(':email', $email, PDO::PARAM_STR );
			$st->execute();
            return $st->rowCount() > 0;
        }



        /**
         * manda la email cn il codice di attivazione edell'account
         * @return boolean se la mail è stata invata
         */
        public function sendActivationMail($uid)
        {
            // cerca la chiave di attivazione nel database, e la mail dell'utente
            $sql = file_get_contents(self::QF_USER_ACTIVATION_KEY);
            $st = $this->pdo->prepare($sql);
            $st->bindParam(":uid", $uid, PDO::PARAM_STR);
            if(!$st->execute()) throw new Exception("impossibile recuperare il codice di attivazione" , 500);
            $user = $st->fetch();
           
            /** crea una mail */
            $body = file_get_contents(self::EF_ACTIVATION_EMAIL);
            $body = str_replace([ "%ACTIVATION_KEY%" ], [ $user->activationKey ], $body);
            
            try {
                $email = new DagMail("hello");
                return $email
                    ->setSubject("Attivazione Account")
                    ->setBody($body)
                    ->addRecipient($user->email)
                    ->send();
            } catch (\Exception $e) {
                throw $e;
                return;
            }
        }







	}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>