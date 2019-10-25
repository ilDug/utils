<?php
require_once __DIR__ . '/../auth.config.php';

/**
 * controller per la gestione delle chiavi RSA
 * sia pubbliche che private
 */
 class KeyChain{

    /** chiave pubblica */
    public $public;

    /** chiave privata */
    public $private;

    /** certificate signing request */
    public $csr;

    /** certificate NON USATO */
    public $crt;


    function __construct($passPhrase = null){
        $pems = $this->listPemFiles();

        
        /** ottine la chiave privata */
        openssl_pkey_export(openssl_pkey_get_private($pems['key']), $this->private);

        /** ottine la chiave pubblica */
        $this->public = openssl_pkey_get_details( openssl_csr_get_public_key($pems['csr']) )['key'];

        /** elabora gli errori openssl */
        while (($e = openssl_error_string()) !== false) {
            // echo ('<br> ' . $e);
            // throw new Exception($e, 500);
        }
    }






    /**
     * ottiene l'elenco delle chiavi salvate nella cartella corrente
     */
	private function listPemFiles() {
		$ArrayEstensioni = array(".pem");
        $pems = array(
            "key" => null,
            "csr" => null,
            "crt" => null
        );
        


		$handle = opendir(RSA_KEY_DIR);
		while (false !== ($file = readdir($handle)))
			{
				if(is_file(RSA_KEY_DIR .  $file))
					{
						$ext = strtolower(substr($file, strrpos($file, "."), strlen($file)-strrpos($file, ".")));//estrae l'estensione del file
						if(in_array($ext,$ArrayEstensioni))
							{
                                // array_push($arrayFilesName,(RSA_KEY_DIR . $file));
                                if(strpos($file, ".key.pem") !== false) $pems['key'] = file_get_contents(RSA_KEY_DIR . $file);
                                if(strpos($file, ".csr.pem") !== false) $pems['csr'] = file_get_contents(RSA_KEY_DIR . $file);
                                if(strpos($file, ".crt.pem") !== false) $pems['crt'] = file_get_contents(RSA_KEY_DIR . $file);
                                // if(strpos($file, ".key.pem") !== false) $pems['key'] = "file://keys/" . $file;
                                // if(strpos($file, ".csr.pem") !== false) $pems['csr'] = "file://keys/" . $file;
                                // if(strpos($file, ".crt.pem") !== false) $pems['crt'] = "file://keys/" . $file;
							}
					}
			}

		$handle = closedir($handle);
		// rsort($arrayFilesName);

		return $pems;
	}




    


    /**
    * genera una chiave privata RSA SHA512
    * e la salva su file
    * @param string $passPhrase
    * @param int bits [optional] il numero di bits della lunghezza della kyave privata generata
    * @return boolean per la creazione della chiave
    */
    public function generateCertificate($passPhrase ,  $bits = 2048){

        // $config = array(
        //     "digest_alg" => "sha256",
        //     "private_key_bits" => $bits,
        //     "private_key_type" => OPENSSL_KEYTYPE_RSA,
        // );

        // $dn = array(
        //     "countryName" => "IT",
        //     "stateOrProvinceName" => "Italia",
        //     "localityName" => "Covo",
        //     "organizationName" => "EURO-KEMICAL SRL",
        //     "organizationalUnitName" => "ITC",
        //     "commonName" => "as.eurokemical.lan",
        //     "emailAddress" => "mdognini@eurokemical.it"
        // );
        
        // // Generate a new private (and public) key pair
        // $privKey = openssl_pkey_new($config);
        // echo $privKey;

        // Generate a certificate signing request
        // $csr = openssl_csr_new($dn, $privKey, array('digest_alg' => 'sha256'));

        // // Generate a self-signed cert, valid for 365 days
        // $x509 = openssl_csr_sign($csr, $ca = null, $privKey, $days = 365, $config);
        
        // $serial = time();
        // $csrout = RSA_KEY_DIR . 'csr' . $serial . '.pem' ;
        // $certout = RSA_KEY_DIR . 'crt' . $serial . '.pem' ;
        // $prkeyout = RSA_KEY_DIR . 'prkey' . $serial . '.pem' ;

        // // Save your private key, CSR and self-signed cert for later use
        // openssl_csr_export($csr, $csrout);
        // openssl_x509_export($x509, $certout);
        // openssl_pkey_export($privKey, $prkeyout, $passPhrase);

        // Show any errors that occurred here
        // while (($e = openssl_error_string()) !== false) {
        //     throw new Exception($e, 500);
        // }
        // $resExport = openssl_pkey_export_to_file($key, $path, $passPhrase);
        // return $resExport;
    }






 }//chiude classe

?>
