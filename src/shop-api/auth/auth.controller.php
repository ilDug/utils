<?php
namespace DAG;

const AUTH_SERVER_URL = "https://shop.dag.lan/as/";
const CA_PATH = __DIR__ . "/keys/dagCA.pem";


class Auth{
    protected $auth_header;

    public $error;
    public $claims;

    public function __construct(){
        $this->auth_header = ( apache_request_headers()['Authorization'] );
        // echo $this->auth_header;
    }


    public function authenticate($throw = false){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => AUTH_SERVER_URL . "authentication/token/claims",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_CAINFO => CA_PATH,
            CURLOPT_CAPATH => CA_PATH,
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

            if($throw && !$x->authenticated) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 401  ' . $this->error, true, 401 );  
                die();
            }
            else return $x->authenticated;
        }
    }


}//chiude la classe
?>


