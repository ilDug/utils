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
require_once __DIR__ .'/../../controllers/products.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();




$router->get('/([.\w]+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->read($productId, 1) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->get('/', function() {
    try {
        $products = new ProductsController();
        echo json_encode( $products->read(false, 1) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->delete('/([.\w]+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->remove($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->post('/', function() {
    $body = json_decode(file_get_contents('php://input'));
    try {
        $products = new ProductsController();
        echo json_encode( $products->add($body->product) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});




$router->put('/hide/([.\w]+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->hide($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});




$router->put('/show/([.\w]+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->show($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});




$router->put('/', function() {
    $body = json_decode(file_get_contents('php://input'));
    try {
        $products = new ProductsController();
        echo json_encode( $products->edit($body->product) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});
     


// Run it!
$router->run();
