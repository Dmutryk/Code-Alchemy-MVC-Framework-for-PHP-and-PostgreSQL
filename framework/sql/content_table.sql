CREATE TABLE `__database_name__`.`__table_name__`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `state_key` VARCHAR(100) NOT NULL,
  `content_key` VARCHAR(100) NOT NULL,
  `content_text` LONGTEXT NOT NULL,
  `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(11),
  `last_modified_date` DATETIME,
  `last_modified_by` INT(11),
  `is_deleted` TINYINT(1) DEFAULT 0,
  `deleted_date` DATETIME,
  `deleted_by` INT,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `IDX___abbr___lookup_key` (`state_key`,`content_key`),
  INDEX `IDX___abbr___created_by` (`created_by`),
  INDEX `IDX___abbr___last_mf_by` (`last_modified_by`),
  INDEX `IDX___abbr___deleted_by` (`deleted_by`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1;
