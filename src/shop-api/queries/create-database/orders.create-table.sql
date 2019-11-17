
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS orders;

CREATE TABLE IF NOT EXISTS  orders (
  `orderId` varchar(32) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `order` json DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  PRIMARY KEY (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


