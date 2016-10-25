CREATE TABLE `__database_name__`.`__table_name__`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sortable_id` INT(11),
  `__model1_name___id` INT(11) NOT NULL,
  `__model2_name___id` INT(11) NOT NULL,
  `__model3_name___id` INT(11) NOT NULL,
  `name` VARCHAR(50),
  `seo_name` VARCHAR(50),
  `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(11),
  `last_modified_date` DATETIME,
  `last_modified_by` INT(11),
  PRIMARY KEY (`id`),
  INDEX `IDX___abbr___created_by` (`created_by`),
  INDEX `IDX___abbr___last_mf_by` (`last_modified_by`),
  INDEX `IDX___abbr_____model1_name__` (`__model1_name___id`),
  INDEX `IDX___abbr_____model2_name__` (`__model2_name___id`),
  INDEX `IDX___abbr_____model3_name__` (`__model3_name___id`),
  CONSTRAINT `FK___abbr_____model1_name___id` FOREIGN KEY (`__model1_name___id`) REFERENCES `__model1_name__` (`id`),
  CONSTRAINT `FK___abbr_____model2_name___id` FOREIGN KEY (`__model2_name___id`) REFERENCES `__model2_name__` (`id`),
  CONSTRAINT `FK___abbr_____model3_name___id` FOREIGN KEY (`__model3_name___id`) REFERENCES `__model3_name__` (`id`)

) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1;
