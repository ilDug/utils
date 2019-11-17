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



// Run it!
$router->run();
?>

