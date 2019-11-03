<?php

// Require composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/account.controller.php';
require_once __DIR__ . '/../controllers/account-activation.controller.php';
require_once __DIR__ . '/../controllers/password.controller.php';
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
    $body = json_decode(file_get_contents('php://input'));

    try {
        $account = new AccountController();
        echo $account->register($body->user);
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $account->pdo = null;
});




/**
 * attiva l'account
 */
$router->get('/resend-activation/(.*)', function ($email) {
    header('Content-Type: application/json');
    try {
        $account = new AccountActivationController();
        echo json_encode($account->resendActivationMail($email));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $account->pdo = null;
});




/**
 * attiva l'account
 */
$router->get('/activate/(.*)', function ($key) {
    header('Content-Type: application/json');
    try {
        $account = new AccountActivationController();
        echo json_encode($account->activate($key));
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
    header('Content-Type: application/json');
    $body = json_decode(file_get_contents('php://input'));

    try {
        $password = new PasswordController();
        echo json_encode($password->recover($body->email));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $password->pdo = null;
});





/**
 * RESTORE PASSWORD - INIT
 */
$router->post('/password/restore/init', function () {
    header('Content-Type: application/json');
    $body = json_decode(file_get_contents('php://input'));

    try {
        $password = new PasswordController();
        echo json_encode($password->restore_init($body->key));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $password->pdo = null;
});






/**
 * RESTORE PASSWORD - change password
 */
$router->post('/password/restore/set', function () {
    header('Content-Type: application/json');
    $body = json_decode(file_get_contents('php://input'));

    try {
        $password = new PasswordController();
        echo json_encode($password->restore($body->key, $body->password));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage(), true, $err->getCode());
    }
    $password->pdo = null;
});






// Run it!
$router->run();
?>