<?php
/*-------------------------------------------------------------------------*/
header('Content-Type: application/json');
require_once __DIR__ . '/../../auth/auth.controller.php';
$auth = new \DAG\Auth();
$auth->authenticate(true);
$auth->authorize("admin", true, "Pagina disponibile solo agli amministratori", true);
/*-------------------------------------------------------------------------*/

// Require composer autoloader
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../controllers/orders.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();



/** il singolo ordine identificato da OrderId */
$router->get('/id/(\w+)', function ($orderId) {
    try {
        $orders = new OrdersController();
        echo json_encode($orders->read($orderId));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});


/** tutti gli ordini di un utente */
$router->get('/uid/(\w+)', function ($uid) {
    try {
        $orders = new OrdersController();
        echo json_encode($orders->read_by_user($uid));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});




/** tutti gli ordini con eventuale limite o paginazione */
$router->get('/', function () {
    $limit = isset($_GET['limit']) ? $_GET['limit'] : null;

    try {
        $orders = new OrdersController();
        echo json_encode($orders->read(false, $limit));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});




$router->post('/', function () {
    $body = json_decode(file_get_contents('php://input'));
    try {
        $orders = new OrdersController();
        echo json_encode($orders->create($body->order));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});




$router->put('/', function () {
    $body = json_decode(file_get_contents('php://input'));
    try {
        $orders = new OrdersController();
        echo json_encode($orders->edit($body->order));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});



// Run it!
$router->run();
