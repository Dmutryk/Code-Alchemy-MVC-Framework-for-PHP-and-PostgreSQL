CREATE TABLE `__database_name__`.`_ca_business_process`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortable_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `seo_name` varchar(100) DEFAULT NULL,
  `href` varchar(50) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX__ca_business_process_created_by` (`created_by`),
  KEY `IDX__ca_business_process_last_mf_by` (`last_modified_by`),
  KEY `IDX__ca_business_process_deleted_by` (`deleted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
