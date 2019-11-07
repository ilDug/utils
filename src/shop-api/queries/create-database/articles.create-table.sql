DROP TABLE IF EXISTS articles;

CREATE TABLE articles (
  `articleId` varchar(32) NOT NULL,
  `orderId` varchar(32) NOT NULL,
  `article` json DEFAULT NULL,
  PRIMARY KEY (`articleId`),
  KEY `fk_order` (`orderId`),
  CONSTRAINT `fk_order` 
	FOREIGN KEY (`orderId`) 
    REFERENCES `orders` (`orderId`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;