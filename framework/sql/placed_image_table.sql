CREATE TABLE `__database_name__`.`__table_name__`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortable_id` int(11) DEFAULT NULL,
  `website_image_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `seo_name` varchar(100) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_placed_image_created_by` (`created_by`),
  KEY `IDX_placed_image_last_mf_by` (`last_modified_by`),
  KEY `IDX_placed_image_deleted_by` (`deleted_by`),
  KEY `IDX_placed_image_website_image` (`website_image_id`),
  CONSTRAINT `FK_placed_image_website_image_id` FOREIGN KEY (`website_image_id`) REFERENCES `website_image` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;