/*
SQLyog Ultimate v12.08 (32 bit)
MySQL - 5.5.42 : Database - tellus
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tellus` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `coupons` */

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_name` varchar(50) DEFAULT NULL,
  `coupon_code` varchar(20) DEFAULT NULL,
  `coupon_use_limit` int(11) DEFAULT NULL,
  `coupon_redeemed` tinyint(1) DEFAULT NULL,
  `coupon_redeem_date` datetime DEFAULT NULL,
  `coupon_expiration` date DEFAULT NULL,
  `coupon_active` tinyint(1) DEFAULT NULL,
  `coupon_created_date` datetime DEFAULT NULL,
  `coupon_modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `coupons` */

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(50) DEFAULT NULL,
  `user_lastname` varchar(50) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_gender` enum('M','F') DEFAULT NULL,
  `user_dob` date DEFAULT NULL,
  `user_password` varchar(32) DEFAULT NULL,
  `user_address_1` varchar(100) DEFAULT NULL,
  `user_address_2` varchar(100) DEFAULT NULL,
  `user_city` varchar(30) DEFAULT NULL,
  `user_state` char(2) DEFAULT NULL,
  `user_facebook_account_id` varchar(50) DEFAULT NULL,
  `user_photo_url` text,
  `user_ip_address` varchar(16) DEFAULT NULL,
  `user_date_joined` datetime DEFAULT NULL,
  `user_verification_code` varchar(32) DEFAULT NULL,
  `user_active` tinyint(1) DEFAULT NULL,
  `user_is_verified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users` */

/*Table structure for table `venues` */

CREATE TABLE `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(100) DEFAULT NULL,
  `venue_google_place_id` varchar(100) DEFAULT NULL,
  `venue_date_added` datetime DEFAULT NULL,
  `venue_image_url` text,
  `venue_address_1` varchar(100) DEFAULT NULL,
  `venue_address_2` varchar(100) DEFAULT NULL,
  `venue_city` varchar(20) DEFAULT NULL,
  `venue_state` char(2) DEFAULT NULL,
  `venue_phone` varchar(16) DEFAULT NULL,
  `venue_email` varchar(100) DEFAULT NULL,
  `venue_lat` decimal(10,8) DEFAULT NULL,
  `venue_lon` decimal(10,8) DEFAULT NULL,
  `venue_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
