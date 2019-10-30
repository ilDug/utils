<?php
require_once __DIR__ . '/../auth.config.php';
require_once __DIR__ . '/../lib/pdo-connection.php';
require_once __DIR__ . '/../classes/jwt.php';
require_once __DIR__ . '/../classes/rsa-key-chain.php';


	/*
	* classe per la gestione degli accessi e degli utenti
	* collegata ad una tabella del database che gestisce gli utenti
	* IDPcode dato dalla forma criptata di mail+timestamp
	*/
	class AccessController
	{


        public  $pdo ;
        function __construct() { $this->pdo = AuthConnection::pdo(); }

		/**
		*	ricerca nel database la corrispondenza tra username e password
		*	resitutisce il token
		*/
		public function login($username, $pw)
		{
            $st = $this->pdo->prepare("SELECT password, active, uid from users WHERE username = :username");
            $st->bindParam(':username', $username, PDO::PARAM_STR );
			$st->execute();
            $o = $st->fetch();
            
			//gestisce l'errore se non esiste l'utente nel database
			if(!$o){
				throw new Exception("Utente non registrato. Procedi prima con la registrazione del tuo account.", 500);
				return false;
			}else{
				if($o->active == 0){
					throw new Exception( " L'utente  non e' ancora stato attivato. Procedere con l'attivazione dell'account . Se ci sono problemi contatta il supporto tecnico", 500);
					return false;
				}else{
					if(!password_verify($pw, $o->password)){
						throw new Exception("password non corretta per questo account.", 500);
						return false;
					}else{
                        $sql = file_get_contents(__DIR__ . "/../queries/get_user_token_data.sql");
                        $st = $this->pdo->prepare($sql);
                        $st->bindParam(':uid', $o->uid, PDO::PARAM_STR );
                        $st->execute();
                        $u = $st->fetch();

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
                            "username"=> $u->username, 
                            "authorizations" => json_decode($u->authorizations)
                        );

                        $keys = new KeyChain();
                        $jwt = (new JWT());
                        $token = $jwt->generate($rawClaims, $keys->private);
                        $jti = $jwt->payload->jti; 

                        $st = $this->pdo->prepare("INSERT INTO accesses (uid, jti) VALUES (:uid, :jti)");
                        $st->bindParam(':uid', $u->uid, PDO::PARAM_STR );
                        $st->bindParam(':jti', $jti, PDO::PARAM_STR );
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
        }
        





        /**
         *  registra un nuovo utente +
        */
        public function register($user){
            /** cotrolla che non manchino i parametri nell'oggetto USER */
            $required_attrs = ["username", "firstName", "familyName", "password", ];
            foreach ($required_attrs as $attr) {
                if(!$user->{$attr}){ 
                    throw new Exception("Attibuto mancante,  riprovare inserendo il valore per " . $attr, 400);
                    return;
                }
            }

            /** EMAIL */
            if(isset($user->email)){
                $user->email = strtolower(trim($user->email));
                if( !filter_var($user->email, FILTER_VALIDATE_EMAIL) ) {
                    throw new Exception("l'email non e' nella forma corretta", 400);
                    return;  
                }
            } else $user->email = null;
            
            $user->password     =   password_hash( $user->password, PASSWORD_DEFAULT);
            $user->uid          =   md5( $user->username . microtime());
            $user->username     =   trim(           (strtolower($user->username)) ) ;
            $user->firstName    =   trim( ucfirst(  (strtolower($user->firstName)) ) ) ; 
            $user->familyName   =   trim( ucfirst(  (strtolower($user->familyName)) ) ) ; 

  
            /** controlla che l'utente esista */
            if($this->username_exists($user->username, $user->email)){
                throw new Exception("un utente con questo username esiste gia' nel database", 400);
                return;             
            }


            /** aggiorne l'email se esiste */
            $sql = isset($user->email) ? file_get_contents(__DIR__ . "/../queries/users_insert_user_w_email.sql") : file_get_contents(__DIR__ . "/../queries/users_insert_user_wo_email.sql");

            $st = $this->pdo->prepare($sql);
            $st->bindParam(':uid', $user->uid, PDO::PARAM_STR );
            $st->bindParam(':username', $user->username, PDO::PARAM_STR );
            $st->bindParam(':password', $user->password, PDO::PARAM_STR );
            $st->bindParam(':firstName', $user->firstName, PDO::PARAM_STR );
            $st->bindParam(':familyName', $user->familyName, PDO::PARAM_STR );

            if(!is_null($user->email))$st->bindParam(':email', $user->email, PDO::PARAM_STR );
            if( !$st->execute() ){ throw new Exception("errore di salvataggio utente - passasggio 1", 500); return; }
            
            $sql = file_get_contents(__DIR__ . "/../queries/get_user_token_data.sql");
            $st = $this->pdo->prepare($sql);
            $st->bindParam(':uid', $user->uid, PDO::PARAM_STR );
            if( !$st->execute() ){ throw new Exception("errore di salvataggio utente - passaggio 2", 500); return; }
            return $st->fetch();
        }



        /** 
         * controlla se un utente è gia stato registrato
         * controlla username e email
         * @return boolean
         */
        private function username_exists($username, $email = null){
            $sql_wo_e = "SELECT uid from users WHERE username = :username";
            $sql_w_e =  "SELECT uid from users WHERE username = :username OR email = :email";
            $sql = is_null($email) ? $sql_wo_e : $sql_w_e;
            $st = $this->pdo->prepare($sql);
            $st->bindParam(':username', $username, PDO::PARAM_STR );
            if(!is_null($email)) {$st->bindParam(':email', $email, PDO::PARAM_STR );}
			$st->execute();
            return $st->rowCount() > 0;
        }






        public function change_password($newpassword, $uid)
        {
            $newpassword = password_hash( $newpassword, PASSWORD_DEFAULT);
            $st = $this->pdo->prepare("UPDATE users SET password = :password WHERE uid = :uid");
            $st->bindParam(':password', $newpassword, PDO::PARAM_STR );
            $st->bindParam(':uid', $uid, PDO::PARAM_STR );
            return $st->execute();
        }

	}//chiude la classe
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ?>