/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.40-0ubuntu0.14.04.1 : Database - udssphalumni
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `calendar_event` */

CREATE TABLE `calendar_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `seo_name` varchar(100) DEFAULT NULL,
  `description` longtext,
  `event_date` date DEFAULT NULL,
  `start_time` varchar(10) DEFAULT NULL,
  `end_time` varchar(10) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_cal_seo_name` (`seo_name`),
  KEY `IDX_cal_created_by` (`created_by`),
  KEY `IDX_cal_last_mf_by` (`last_modified_by`),
  KEY `IDX_cal_deleted_by` (`deleted_by`),
  KEY `IDX_cal_calendar` (`calendar_id`),
  CONSTRAINT `FK_cal_calendar_id` FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
