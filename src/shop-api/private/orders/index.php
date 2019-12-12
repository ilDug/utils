<?php
/*-------------------------------------------------------------------------*/
header('Content-Type: application/json');
require_once __DIR__ . '/../../auth/auth.controller.php';

// Require composer autoloader
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../controllers/orders.controller.php';
/*-------------------------------------------------------------------------*/

// Create Router instance
$router = new \Bramus\Router\Router();



/** il singolo ordine identificato da OrderId */
$router->get('/(\w+)', function ($orderId) {
    $auth = new \DAG\Auth();
    $auth->authenticate(true);

    try {
        $orders = new OrdersController();
        $list = $orders->read_by_user($auth->claims->uid);
        $found = false;

        /** filtra per l'identificativo dell'ordine */
        foreach ($list as $order) {
            if ($order->orderId == $orderId) {
                echo json_encode($order);
                $found = true;
                break;
            }
        }

        /** nel caso non trovasse l'ordine restituisce null */
        if (!$found) echo json_encode(null);
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});




/** tutti gli ordini con eventuale limite o paginazione */
$router->get('/', function () {
    $auth = new \DAG\Auth();
    $auth->authenticate(true);

    try {
        $orders = new OrdersController();
        echo json_encode($orders->read_by_user($auth->claims->uid));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $orders->pdo = null;
});





$router->post('/', function () {
    $auth = new \DAG\Auth();
    $auth->authenticate(true);
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
    $auth = new \DAG\Auth();
    $auth->authenticate(true);
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
