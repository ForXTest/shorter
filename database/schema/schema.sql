SET NAMES 'utf8';

USE `shortLinks`;

CREATE TABLE IF NOT EXISTS `short_links` (
  `short` varchar(10) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`hash`),
  INDEX (`short`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
