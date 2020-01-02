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
require_once __DIR__ . '/../../controllers/warehouse.controller.php';


// Create Router instance
$router = new \Bramus\Router\Router();




// $router->get('/([.\w]+)', function($productId) {
//     try {
//         $warehouse = new WarehouseController();
//         echo json_encode( $warehouse->read($productId, 1) );
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
//     }
//     $warehouse->pdo = null;
// });





// $router->get('/', function() {
//     try {
//         $warehouse = new WarehouseController();
//         echo json_encode( $warehouse->read(false, 1) );
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
//     }
//     $warehouse->pdo = null;
// });





// $router->delete('/([.\w]+)', function($productId) {
//     try {
//         $warehouse = new WarehouseController();
//         echo json_encode( $warehouse->remove($productId) );
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
//     }
//     $warehouse->pdo = null;
// });




/**  /admin/warehouse/article/?q=1  */
$router->post('/article', function () {
    $body = json_decode(file_get_contents('php://input'));
    $qty = (!isset($_GET['q']) || !$_GET['q']) ? 1 : (int) $_GET['q'];

    try {
        $warehouse = new WarehouseController();
        echo json_encode($warehouse->add_articles($body->article, $qty));
    } catch (\Exception $err) {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
    }
    $warehouse->pdo = null;
});




// $router->put('/hide/([.\w]+)', function($productId) {
//     try {
//         $warehouse = new WarehouseController();
//         echo json_encode( $warehouse->hide($productId) );
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
//     }
//     $warehouse->pdo = null;
// });




// $router->put('/show/([.\w]+)', function($productId) {
//     try {
//         $warehouse = new WarehouseController();
//         echo json_encode( $warehouse->show($productId) );
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
//     }
//     $warehouse->pdo = null;
// });




// $router->put('/', function() {
//     $body = json_decode(file_get_contents('php://input'));
//     try {
//         $warehouse = new WarehouseController();
//         echo json_encode( $warehouse->edit($body->product) );
//     } catch (\Exception $err) {
//         header($_SERVER['SERVER_PROTOCOL'] . ' ' . $err->getCode() . ' ' . $err->getMessage());
//     }
//     $warehouse->pdo = null;
// });



// Run it!
$router->run();