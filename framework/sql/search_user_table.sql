CREATE
  ALGORITHM=TEMPTABLE
  SQL SECURITY DEFINER
VIEW
  `search_user` AS (
SELECT
  `u`.`id` AS `id`,
  CONCAT_WS(' ',`u`.`email`,`u`.`first_name`,`u`.`last_name`) AS `term`,
  CONCAT_WS(' ',`u`.`first_name`,`u`.`last_name`) AS `name`
FROM `user` `u`
WHERE (`u`.`is_deleted` = 0));
