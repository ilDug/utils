USE users;

DROP TABLE IF EXISTS `authorizations`;

CREATE TABLE `authorizations` (
  `authorization` varchar(32) NOT NULL,
  `description` text,
  PRIMARY KEY (`authorization`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO authorizations (authorization, description)
VALUES
    ('basic', 'autorizzazione iniziale basilare di qualsiasi utente NON ATTIVO')
    ('activated', 'autorizzazione iniziale per l\'utente ATTIVO')