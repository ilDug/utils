DROP TABLE IF EXISTS `accesses`;


CREATE TABLE `accesses` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jti` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;