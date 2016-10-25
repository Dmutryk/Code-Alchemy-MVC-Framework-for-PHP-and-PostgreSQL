CREATE TABLE `__database_name__`.`__table_name__`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortable_id` int(11) DEFAULT NULL,
  `website_page_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_flex_slider_created_by` (`created_by`),
  KEY `IDX_flex_slider_last_mf_by` (`last_modified_by`),
  KEY `IDX_flex_slider_deleted_by` (`deleted_by`),
  KEY `IDX_flex_slider_website_page` (`website_page_id`),
  CONSTRAINT `FK_flex_slider_website_page_id` FOREIGN KEY (`website_page_id`) REFERENCES `website_page` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

