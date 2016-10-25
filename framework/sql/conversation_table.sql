CREATE TABLE `__database_name__`.`__table_name__`(
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`user_one` int(11) NOT NULL,
`user_two` int(11) NOT NULL,
`ip` varchar(30) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
  `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(11),
  `last_modified_date` DATETIME,
  `last_modified_by` INT(11),
  `is_deleted` TINYINT(1) DEFAULT 0,
  `deleted_date` DATETIME,
  `deleted_by` INT,
FOREIGN KEY (user_one) REFERENCES `user` (`id`),
FOREIGN KEY (user_two) REFERENCES `user`(`id`)
);