CREATE TABLE `__database_name__`.`email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_key` varchar(30) NOT NULL,
  `subject` varchar(100) DEFAULT '[__appname__] New email notification',
  `text` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_key` (`template_key`),
  KEY `IDX_et_created_by` (`created_by`),
  KEY `IDX_et_last_mf_by` (`last_modified_by`),
  KEY `IDX_et_deleted_by` (`deleted_by`)

) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
