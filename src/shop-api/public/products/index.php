<?php
/*-------------------------------------------------------------------------*/
header('Content-Type: application/json');
/*-------------------------------------------------------------------------*/

// Require composer autoloader
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../controllers/products.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();




$router->get('/(\w+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->read($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->get('/', function() {
    try {
        $products = new ProductsController();
        echo json_encode( $products->read() );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});

     


// Run it!
$router->run();
?>