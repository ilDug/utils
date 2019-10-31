<?php

// Require composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DRI__ . '/../controllers/account.controller.php';
require_once __DIR__ . '/../controllers/authorization.controller.php';


// echo '{"name":"pippo"}';
// Create Router instance
$router = new \Bramus\Router\Router();


/**
 * login con username e password
 */
$router->post('/login', function() {
    $body = json_decode(file_get_contents('php://input'));

    try {
        $account = new AccountController();
        echo $account->login($body->email, $body->password);
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $account->pdo = null;
});



/**
 * registra un nuovo utente e ritorna il token di login
 */
$router->post('/register', function() {
    header('Content-Type: application/json');
    $body = json_decode(file_get_contents('php://input'));

    try {
        $account = new AccountController();
        echo json_encode($account->register($body->user));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $account->pdo = null;
});




/**
 * attiva l'account
 */
$router->get('/activate/(.*)', function ($key) {
    try {
        $account = new AccountController();
        echo $account->activate($key);
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $account->pdo = null;
});






/**
 * RECOVER PASSWORD
 * permette di creare una chiave provvisoria per il successivo ripristino
 */
$router->post('/password/recover', function () {
    $body = json_decode(file_get_contents('php://input'));

    try {
        $password = new PasswordController();
        echo $password->recover($body->email);
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $password->pdo = null;
});





/**
 * RESTORE PASSWORD - INIT
 */
$router->post('/password/restore/init', function () {
    // $body = json_decode(file_get_contents('php://input'));

    // try {
    //     $ctrl = new PasswordController();
    //     echo $ctrl->initRestore($body->key);
    // } catch (\Exception $err) {
    //     header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    // }
    // $ctrl->pdo = null;
});






/**
 * RESTORE PASSWORD - change password
 */
$router->post('/password/restore/set', function () {
    // $body = json_decode(file_get_contents('php://input'));

    // try {
    //     $ctrl = new PasswordController();
    //     echo $ctrl->restore($body->key, $body->password);
    // } catch (\Exception $err) {
    //     header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    // }
    // $ctrl->pdo = null;
});






// Run it!
$router->run();
?>