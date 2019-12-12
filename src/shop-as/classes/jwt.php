<?php
require_once __DIR__ . '/../vendor/autoload.php';



/**
 * struttura tipica del payload del JWT
 */
class JwtClaims
{
    // iss (issuer): Issuer of the JWT
    public $iss;

    // sub (subject): Subject of the JWT (the user)
    public $sub;

    // aud (audience): Recipient for which the JWT is intended
    public $aud;

    // exp (expiration time): Time after which the JWT expires
    public $exp;

    // nbf (not before time): Time before which the JWT must not be accepted for processing
    public $nbf;

    // iat (issued at time): Time at which the JWT was issued; can be used to determine age of the JWT
    public $iat;

    // jti (JWT ID): Unique identifier; can be used to prevent the JWT from being replayed (allows a token to be used only once)
    public $jti;

    // user ID
    public $uid;

    // durata del token in secondi, per calcolare  $epx
    public $duration;




    function __construct($claims = [])
    {
        foreach ($claims as $key => $value) {
            $this->{$key}  = $value;
        }

        $this->duration = isset($this->duration) ? $this->duration : (60 * 60 * 12);
        $this->iat = isset($this->iat) ? $this->iat : time();
        $this->nbf = isset($this->nbf) ? $this->nbf : time();
        $this->exp = isset($this->exp) ? $this->exp : time() + $this->duration;
        $this->jti = isset($this->jti) ? $this->jti : $this->generateJTI();
        $this->iss = isset($this->iss) ? $this->iss : "as.dag.lan";
        $this->aud = isset($this->aud) ? $this->aud : "dag.lan";
    }




    /** genera un identificativo del token e ritona la stringa 32 caratteri */
    private function generateJTI()
    {
        $jti = '';
        /** itera tutte le proprietà della classe  */
        foreach ($this as $key => $value) {
            $jti  .= (string) $value;
        }
        return md5($jti);
    }
}







class JwtValidationSettings
{
    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = [
            'jti' => null,
            'iss' => null,
            'aud' => null,
            'sub' => null
        ];

        foreach ($this->fields as $claim => $value) {
            if (isset($fields[$claim])) {
                $this->fields[$claim] = $fields[$claim];
            }
        }
    }
}



use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Rsa\Sha256; // you can use Lcobucci\JWT\Signer\Ecdsa\Sha256 if you're using ECDSA keys
use Lcobucci\JWT\Signer\Key;



class JWT
{



    public $token;
    public $header;
    public $payload;
    public $valid = null;
    public $verified = null;
    public $pristine = true;
    public $checked = false;


    function  __construct($jwt =  null)
    {
        if (!is_null($jwt)) {
            $this->parse($jwt);
        }
    }

    // $rawClaims = array(
    //     "iss" => "",
    //     "aud" => "",
    //     "sub" => "",
    //     "uid" => "",
    //     "iat" => null,
    //     "nbf" => null,
    //     "duration" => null,
    //     "exp" => null,
    //     "jti" => null
    // );

    /**
     * Genra il JWT
     * @param $claims,  array dei dati da inserire nel payload
     * 
     */
    public function generate($claims,  $privateKey)
    {
        /** controlla che il parametro dei claims sia un array */
        if (!is_array($claims)) {
            throw new Exception("il parametro payload non è un array", 400);
            die();
        }

        /** crea l'istanza dell'oggetto CLAIMS */
        $jwtClaims = new JwtClaims($claims);

        /** crea l'istanza della classe Key */
        $privateKeyObj = new Key($privateKey);

        /** crea l'istanza della classe per la firma */
        $signer = new Sha256();

        /** crea l'istanza del costruttore del token */
        $builder = (new Builder());
        foreach ($jwtClaims as $key => $value) {
            $builder = $builder->withClaim($key, $value);
        }

        /** creates a signature using your private key and Retrieves the generated token */
        $token = $builder->getToken($signer,  $privateKeyObj);

        //compila anche le altre proprietà della classe
        $this->parse($token);

        //eventualmente ritorna la stringa
        return $token;
    }



    /**
     * funzione principale che esegue la verifica e la validazione
     */
    public function check($publicKey)
    {
        $this->checked = true;
        $this->pristine = false;
        $this->verify($publicKey);
        $this->validate();

        return $this->valid && $this->verified;
    }





    // $jvs = new JwtValidationSettings([
    //             'jti' => null,
    //             'iss' => null,
    //             'aud' => null,
    //             'sub' => null
    //         ]);
    /**
     * valida i claims JTI ,  ISS ,  AUD e le date  enunciate dal MANIFEST
     */
    private function validate(JwtValidationSettings $jvs = null)
    {
        $vd = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        // $vd->setIssuer($jvs['iss']);
        // $vd->setAudience($jvs['aud']);
        // $vd->setId($jvs['jti']);
        // $vd->setSubject($jvs['sub']);
        $this->valid = $this->token->validate($vd);
    }


    /**
     * vrficia che la SIGNATURE sia corretta ( usa RSA public key)
     */
    private function verify($publicKey)
    {
        $signer = new Sha256();
        $publicKeyObj = new Key($publicKey);
        $this->verified = $this->token->verify($signer, $publicKeyObj);
    }


    /**
     * imposta jwt da stringa in ingresso
     */
    private function parse($jwt)
    {
        $token = (new Parser())->parse((string) $jwt);
        $this->token = $token;
        $this->header = $token ? $token->getHeaders() : null;

        /** permette di elaborare eventuali oggetti JSON annidiati */
        $this->payload = $token ? json_decode(json_encode($token->getClaims())) : null;
    }
}//chiude classe
