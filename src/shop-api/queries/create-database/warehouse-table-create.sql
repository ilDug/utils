DROP TABLE IF EXISTS warehouse;

CREATE TABLE warehouse (
  `articleId` INT NOT NULL AUTO_INCREMENT,
  `productId` VARCHAR(32) NOT NULL,
  `sku` VARCHAR(16) NULL DEFAULT NULL,
  `batch` VARCHAR(8) NULL DEFAULT NULL,
  `item` INT NULL DEFAULT NULL,
  `available` INT NULL DEFAULT 1,
  `dateIn` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `dateOut` TIMESTAMP NULL DEFAULT NULL,
  `dateExpiry` TIMESTAMP NULL DEFAULT NULL,
  `status` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`articleId`, `productId`),
  KEY `fk_product_wharehouse` (`productId`),
  CONSTRAINT `fk_product_wharehouse` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`)
) ENGINE = INNODB DEFAULT CHARSET = UTF8MB4;