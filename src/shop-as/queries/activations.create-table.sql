
DROP TABLE IF EXISTS `activations`;


CREATE TABLE `activations` (
  `activationKey` varchar(255) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `generationKeyDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activationDate` timestamp DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (activationKey, uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;