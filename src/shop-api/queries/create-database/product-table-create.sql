DROP TABLE IF EXISTS shop.products;


CREATE TABLE IF NOT EXISTS `shop`.`products` (
  `productId` VARCHAR(32) NOT NULL,
  `identifier` VARCHAR(255) NOT NULL,
  `sku` VARCHAR(255) NOT NULL,
  `brand` VARCHAR(255) NOT NULL,
  `hidden` INT NOT NULL DEFAULT 1,
  `product` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`productId`)
)
ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;