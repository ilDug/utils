<?php
header('Content-Type: application/json');
// Require composer autoloader
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../authentication/authentication.controller.php';
require __DIR__ . '/authorization.controller.php';


// echo '{"name":"pippo"}';
// Create Router instance
$router = new \Bramus\Router\Router();


/**
 * login con username e password
 */
$router->get('/check/(\w+)', function($permission) {
    $auth_header = ( apache_request_headers()['Authorization'] );
    $auth =  new AuthenticationController() ;
    if($auth->authenticate($auth_header) !== true) { echo json_encode(false); die(); }
    if(!$permission) { echo json_encode(false); die(); }

    $auth_list = (!$auth->claims->authorizations || ($auth->claims->authorizations[0] === null && count($auth->claims->authorizations) === 1 ) ) 
        ? [] : $auth->claims->authorizations;

    echo json_encode( in_array( $permission, $auth_list ) );
});


$router->get('/list', function() {
    $auth_header = ( apache_request_headers()['Authorization'] );
    $auth =  new AuthorizationController() ;
    if($auth->authenticate($auth_header) !== true) { echo json_encode(false); die(); }

    try {
       echo json_encode( $auth->authList() );
       $auth->pdo = null;
    } catch (\Exception $err) {
       header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
});




$router->post('/grant', function() {
    $auth_header = ( apache_request_headers()['Authorization'] );
    $auth = new AuthorizationController();
    $auth->authenticate($auth_header);

    $body = json_decode(file_get_contents('php://input'));
    $permission = $body->permission;
    $uid = $body->uid;

    try {
       echo json_encode( $auth->assignAuthorization($permission, $uid) );
       $auth->pdo = null;
    } catch (\Exception $err) {
       header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
});




$router->post('/deny', function() {
    $auth_header = ( apache_request_headers()['Authorization'] );
    $auth = new AuthorizationController();
    $auth->authenticate($auth_header);
    
    $body = json_decode(file_get_contents('php://input'));
    $permission = $body->permission;
    $uid = $body->uid;

    try {
       echo json_encode( $auth->denyAuthorization($permission, $uid) );
       $auth->pdo = null;
    } catch (\Exception $err) {
       header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
});
    


// Run it!
$router->run();
?>