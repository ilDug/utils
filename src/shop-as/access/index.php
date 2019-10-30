<?php

// Require composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DRI__ . '/../controllers/access.controller.php';
require_once __DIR__ . '/../controllers/authorization.controller.php';


// echo '{"name":"pippo"}';
// Create Router instance
$router = new \Bramus\Router\Router();


/**
 * login con username e password
 */
$router->post('/login', function() {
    $body = json_decode(file_get_contents('php://input'));

    if(!isset($body->username) || $body->username === null ){
        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Nome utente assente ', true, 400); die();
    }

    if(!isset($body->password) || $body->password === null ){
        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Password assente ', true, 400); die();
    }

    try {
        $ctrl = new AccessController();
        echo $ctrl->login($body->username, $body->password);
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
});



/**
 * registra un nuovo utente
 */
$router->post('/register', function() {
    header('Content-Type: application/json');
    $ctrl = new AccessController();
    $body = json_decode(file_get_contents('php://input'));

    try {
        echo json_encode($ctrl->register($body->user));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
});



/**
 * modifica la password
 * @param $body->uid + $body->newpassword
 */
// $router->put('/change-password', function() {
    
//     /***************************************************************** */
//     /** AUTENTICAZIONE */
//     $auth_header = ( apache_request_headers()['Authorization'] );
//     $auth =  new AuthorizationController() ;
//     $msg = $auth->authenticate($auth_header);
//     if($msg !== true) { header($_SERVER['SERVER_PROTOCOL'] . ' 401 ' . $msg); die();}
//     /***************************************************************** */
    
//     header('Content-Type: application/json');
//     $body = json_decode(file_get_contents('php://input'));
    
//     try {
//         if(!$body->newpassword) { throw new Exception("password mancante", 404); die(); }
//         if(!$body->uid) { throw new Exception("uid mancante", 404); die(); }
//         /** AUTORIZZAZIONE */
//         /** uid del token e uid della richiesta devono coincidere in modo che nessun utente possa modificare la password ad altri */
//         if($auth->claims->uid != $body->uid) {
//             /** un requisito deve essere quello di avere il permesso per cambiare la password a chiunque !!! solo ADMIN */
//             if(!$auth->claims->authorizations['change_password']) { 
//                 throw new Exception("non possiedi l'autorizzazione per modificare la password di questo utente", 401);
//                 die();
//             }
//         }
    
//         $ctrl = new AccessController();
//         echo json_encode($ctrl->change_password($body->newpassword, $body->uid));
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
//     }
// });






// Run it!
$router->run();
?>