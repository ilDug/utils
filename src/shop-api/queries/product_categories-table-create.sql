CREATE TABLE IF NOT EXISTS `shop`.`product_categories` (
  `productId` VARCHAR(32) NOT NULL,
  `categoryId` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`productId`, `categoryId`),
  INDEX `fk_category_idx` (`categoryId` ASC) VISIBLE,
  CONSTRAINT `fk_product`
    FOREIGN KEY (`productId`)
    REFERENCES `shop`.`products` (`productId`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_category`
    FOREIGN KEY (`categoryId`)
    REFERENCES `shop`.`categories` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE = InnoDB

