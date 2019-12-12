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
require_once __DIR__ .'/../../controllers/categories.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();




$router->get('/(\w+)', function($name) {
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->read($name) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});





$router->get('/', function() {
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->read() );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});





$router->delete('/(\w+)', function($name) {
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->remove($name) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});





$router->post('/', function() {
    $body = json_decode(file_get_contents('php://input'));
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->add($body->category) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});




$router->put('/assign/(\w+)/([.\w]+)', function($cat_name, $productId) {
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->assign($cat_name, $productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});




$router->put('/detach/(\w+)/([.\w]+)', function($cat_name, $productId) {
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->detach($cat_name, $productId) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});




$router->put('/(\w+)', function($old_name) {
    $body = json_decode(file_get_contents('php://input'));
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->edit($body->category, $old_name) );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});
     


// Run it!
$router->run();
