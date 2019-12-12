<?php
require_once __DIR__ . '/../lib/shop.pdo.php';
require_once __DIR__ . '/../lib/utils.php';

class WareHouseController
{
    public $pdo;

    function __construct()
    {
        $this->pdo = ShopConnection::pdo();
    }


    const FIELDS = [
        "productId",
        "sku",
        "batch",
        "item",
        "status"
    ];

    public function add_article($article)
    {
        if (!DAG\UTILS\checkFields($article, self::FIELDS)) {
            return false;
        }
    }
}

// CREATE TABLE warehouse (


//   `articleId` INT NOT NULL AUTO_INCREMENT,
//   `productId` VARCHAR(32) NOT NULL,
//   `sku` VARCHAR(255) NULL DEFAULT NULL,
//   `available` INT NULL DEFAULT 1,
//   `dateIn` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
//   `dateOut` TIMESTAMP NULL DEFAULT NULL,
//   `dateExpiry` TIMESTAMP NULL DEFAULT NULL,
//   `supplyId` VARCHAR(32) NULL DEFAULT NULL,


//   PRIMARY KEY (`articleId`, `productId`),
//   CONSTRAINT `fk_product_wharehouse` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`)
