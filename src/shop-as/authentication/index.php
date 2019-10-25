<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/authentication.controller.php';


// Create Router instance   
$router = new \Bramus\Router\Router();



$router->get('/token/claims', function() {
        $auth_header = ( apache_request_headers()['Authorization'] );
        $auth =  new AuthenticationController() ;
        $msg = $auth->authenticate($auth_header);
        echo 
            ( $msg === true ) 
        ?   
            json_encode( (object) [ "authenticated" => true,   "response" => $auth->claims ])
        :
            json_encode( (object) [ "authenticated" => false,   "response" => $msg ] );
});



// Run it!
$router->run();
?>