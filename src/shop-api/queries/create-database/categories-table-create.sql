DROP TABLE IF EXISTS shop.categories;

CREATE TABLE IF NOT EXISTS `shop`.`categories` (
  `name` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB