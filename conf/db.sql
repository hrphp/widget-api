
CREATE TABLE `widgets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `modifiedAt` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`color`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `widgets`
(`name`,
`color`,
`createdAt`,
`modifiedAt`)
VALUES
('My Favorite Widget',
'black',
CURRENT_TIMESTAMP,
CURRENT_TIMESTAMP),
('My Least Favorite Widget',
'magenta',
CURRENT_TIMESTAMP,
CURRENT_TIMESTAMP),
('Another Widget',
'red',
CURRENT_TIMESTAMP,
CURRENT_TIMESTAMP),
('A Random Widget',
'blue',
CURRENT_TIMESTAMP,
CURRENT_TIMESTAMP),
('Yet Another Widget',
'green',
CURRENT_TIMESTAMP,
CURRENT_TIMESTAMP);
