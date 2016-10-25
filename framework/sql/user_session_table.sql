CREATE TABLE `__database_name__`.`user_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) DEFAULT NULL,
  `session` blob,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_consumed` tinyint(4) DEFAULT '0',
  `consumption_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1626 DEFAULT CHARSET=utf8;
