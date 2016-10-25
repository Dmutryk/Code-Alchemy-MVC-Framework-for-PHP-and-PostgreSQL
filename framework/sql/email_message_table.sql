CREATE TABLE `__database_name__`.`__table_name__`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(40) NOT NULL,
  `message` text NOT NULL,
  `is_sent` tinyint(1) DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_gue_created_by` (`created_by`),
  KEY `IDX_gue_last_mf_by` (`last_modified_by`),
  KEY `IDX_gue_deleted_by` (`deleted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

