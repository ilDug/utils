DROP TABLE IF EXISTS sku;

CREATE TABLE sku (
  `family` VARCHAR(4) NOT NULL,
  `familyDescription` TEXT NULL DEFAULT NULL,

  `brand` VARCHAR(2) NULL DEFAULT NULL,
  `familyDescription` TEXT NULL DEFAULT NULL,

  `element` VARCHAR(2) NULL DEFAULT NULL,
--   `year` INT NULL DEFAULT NULL,
--   `batch` VARCHAR(2) NULL DEFAULT NULL,
--   `item` INT NULL DEFAULT 1,
--   `storage` VARCHAR(32) NULL DEFAULT NULL,
  PRIMARY KEY (`sku`),
) ENGINE = INNODB DEFAULT CHARSET = UTF8MB4;