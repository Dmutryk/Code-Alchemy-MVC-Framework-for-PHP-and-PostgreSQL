CREATE TABLE `__database_name__`.`__table_name__`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sortable_id` INT(11),
  `__model_name___id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `seo_name` VARCHAR(100),
  `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(11),
  `last_modified_date` DATETIME,
  `last_modified_by` INT(11),
  `is_deleted` TINYINT(1) DEFAULT 0,
  `deleted_date` DATETIME,
  `deleted_by` INT,
  PRIMARY KEY (`id`),
  INDEX `IDX___abbr___created_by` (`created_by`),
  INDEX `IDX___abbr___last_mf_by` (`last_modified_by`),
  INDEX `IDX___abbr___deleted_by` (`deleted_by`),
  INDEX `IDX___abbr_____model_name__` (`__model_name___id`),
  CONSTRAINT `FK___abbr_____model_name___id` FOREIGN KEY (`__model_name___id`) REFERENCES `__model_name__` (`id`)

) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1;
