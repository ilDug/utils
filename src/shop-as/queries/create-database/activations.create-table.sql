
DROP TABLE IF EXISTS `activations`;


CREATE TABLE `activations` (
  `activationKey` varchar(255) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `generationKeyDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activationDate` timestamp DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (activationKey, uid),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


