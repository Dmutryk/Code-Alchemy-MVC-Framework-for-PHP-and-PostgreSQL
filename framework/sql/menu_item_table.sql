CREATE TABLE `__database_name__`.`__table_name__`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortable_id` INT(11),
  `parent_menu_item_id` int(11) DEFAULT NULL,
  `label` varchar(40) NOT NULL,
  `dropdown_sort_order` int(11) DEFAULT NULL,
  `href` varchar(100) DEFAULT NULL,
  `is_dropdown` tinyint(1) DEFAULT '0',
  `display_language` enum('en','es') DEFAULT 'es',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_menu_item_unique_label` (`label`),
  KEY `IDX_menu_item_created_by` (`created_by`),
  KEY `IDX_menu_item_last_mf_by` (`last_modified_by`),
  KEY `IDX_menu_item_deleted_by` (`deleted_by`),
  KEY `IDX_menu_item_parent` (`parent_menu_item_id`),
  CONSTRAINT `FK_menu_item_parent_menu_item` FOREIGN KEY (`parent_menu_item_id`) REFERENCES `__table_name__` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

