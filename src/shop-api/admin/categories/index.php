<?php
/*-------------------------------------------------------------------------*/
header('Content-Type: application/json');
require_once __DIR__ . '/../../auth/auth.controller.php';
$auth = new \DAG\Auth();
$auth->authenticate(true);
if(!in_array("admin" ,$auth->claims->authorizations)) 
    { header($_SERVER['SERVER_PROTOCOL'] . ' 401 Pagina disponibile solo agli amministratori'); die(); }
/*-------------------------------------------------------------------------*/

// Require composer autoloader
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../controllers/categories.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();




$router->get('/(\w+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->findOne($productId, 1) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->get('/', function() {
    try {
        $products = new ProductsController();
        echo json_encode( $products->read(1) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->delete('/(\w+)', function($productId) {
    try {
        $products = new ProductsController();
        echo json_encode( $products->remove($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});





$router->post('/', function() {
    $product = json_decode(file_get_contents('php://input'));
    try {
        $products = new ProductsController();
        echo json_encode( $products->add($product) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});




$router->put('/hide/(\w+)', function($productId) {
    $product = json_decode(file_get_contents('php://input'));
    try {
        $products = new ProductsController();
        echo json_encode( $products->hide($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});




$router->put('/show/(\w+)', function($productId) {
    $product = json_decode(file_get_contents('php://input'));
    try {
        $products = new ProductsController();
        echo json_encode( $products->show($productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});




$router->put('/(\w+)', function($productId) {
    $product = json_decode(file_get_contents('php://input'));
    try {
        $products = new ProductsController();
        echo json_encode( $products->edit($product) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $products->pdo = null;
});
     


// Run it!
$router->run();
?>