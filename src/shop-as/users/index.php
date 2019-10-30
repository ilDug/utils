<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../authentication/authentication.controller.php';
require_once __DIR__ . '/users.controller.php';

// Create Router instance   
$router = new \Bramus\Router\Router();



$router->get('/', function() {
    // $body = json_decode(file_get_contents('php://input'));
    // $action = $body->action;

    // /***************************************************************** */
    // $auth_header = ( apache_request_headers()['Authorization'] );
    // $auth =  new AuthenticationController() ;
    // $msg = $auth->authenticate($auth_header);
    // if($msg !== true) { header($_SERVER['SERVER_PROTOCOL'] . ' 401 ' . $msg); die();}
    // /***************************************************************** */

    // try {
    //     $ctrl = new UsersController();
    //     echo json_encode($ctrl->users_list() );
    // } catch (Exception $err) {
    //     header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    // }


});


// Run it!
$router->run();
?>