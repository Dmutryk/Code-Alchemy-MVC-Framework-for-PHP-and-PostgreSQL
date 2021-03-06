CREATE TABLE `__database_name__`.`__table_name__`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
    `__model_name_lc___id` INT(11) NOT NULL,
  `sortable_id` int(11),
  `image_filename` varchar(100) DEFAULT NULL,
  `caption` varchar(255) NOT NULL,
  `seo_name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_unique_filename` (`image_filename`),
  KEY `IDX___model_name___created_by` (`created_by`),
  KEY `IDX___model_name___last_mf_by` (`last_modified_by`),
  KEY `IDX___model_name___deleted_by` (`deleted_by`),
    INDEX `IDX___abbr_____model_name__` (`__model_name_lc___id`),
  CONSTRAINT `FK___abbr_____model_name_lc__id` FOREIGN KEY (`__model_name_lc___id`) REFERENCES `__model_name_lc__` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
