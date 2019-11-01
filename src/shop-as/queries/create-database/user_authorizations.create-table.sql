DROP TABLE IF EXISTS `user_authorizations`;

CREATE TABLE `user_authorizations` (
  `uid` varchar(32) NOT NULL,
  `authorization` varchar(32) NOT NULL,
  PRIMARY KEY (`uid`,`authorization`),
  KEY `fk_user_authorizations_authorizations1_idx` (`authorization`),
  KEY `fk_user_authorizations_users1_idx` (`uid`),
  CONSTRAINT `fk_user_authorizations_authorizations1` FOREIGN KEY (`authorization`) REFERENCES `authorizations` (`authorization`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_authorizations_users1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;