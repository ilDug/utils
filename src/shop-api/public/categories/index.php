<?php
/*-------------------------------------------------------------------------*/
header('Content-Type: application/json');
/*-------------------------------------------------------------------------*/

// Require composer autoloader
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../controllers/categories.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();


$router->get('/', function() {
    try {
        $categories = new CategoriesController();
        echo json_encode( $categories->read() );
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $categories->pdo = null;
});

     


// Run it!
$router->run();
?>