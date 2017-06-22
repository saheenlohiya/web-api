/*
SQLyog Ultimate v12.08 (32 bit)
MySQL - 5.6.35 : Database - tellus_tests
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) DEFAULT NULL,
  `user_firstname` varchar(50) DEFAULT NULL,
  `user_lastname` varchar(50) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_username` varchar(25) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_gender` enum('M','F') DEFAULT NULL,
  `user_dob` date DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_address_1` varchar(100) DEFAULT NULL,
  `user_address_2` varchar(100) DEFAULT NULL,
  `user_city` varchar(30) DEFAULT NULL,
  `user_state` char(2) DEFAULT NULL,
  `user_zip` varchar(10) DEFAULT NULL,
  `user_lat` decimal(10,7) DEFAULT NULL,
  `user_lon` decimal(10,7) DEFAULT NULL,
  `user_facebook_account_id` varchar(50) DEFAULT NULL,
  `user_photo_url` text,
  `user_ip_address` varchar(16) DEFAULT NULL,
  `user_date_joined` datetime DEFAULT NULL,
  `user_date_modified` datetime DEFAULT NULL,
  `user_verification_code` varchar(32) DEFAULT NULL,
  `user_active` tinyint(1) DEFAULT '1',
  `user_is_verified` tinyint(1) DEFAULT '0',
  `user_access_token` varchar(255) DEFAULT NULL,
  `user_auth_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`user_email`,`user_phone`),
  UNIQUE KEY `user_username` (`user_username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users` */

/*Table structure for table `users_venues_claims` */

DROP TABLE IF EXISTS `users_venues_claims`;

CREATE TABLE `users_venues_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `venue_claim_date` datetime DEFAULT NULL,
  `venue_claim_status` enum('pending','active','suspended') DEFAULT 'pending',
  `venue_claim_verified_date` datetime DEFAULT NULL,
  `venue_claim_verify_admin` int(11) DEFAULT NULL,
  `venue_claim_update_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `users_venues_claims_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_venues_claims_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_claims` */

/*Table structure for table `users_venues_coupons` */

DROP TABLE IF EXISTS `users_venues_coupons`;

CREATE TABLE `users_venues_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `venue_coupon_id` int(11) DEFAULT NULL,
  `user_venue_coupon_award_date` datetime DEFAULT NULL,
  `user_venue_coupon_exp` date DEFAULT NULL,
  `user_venue_coupon_active` tinyint(1) DEFAULT '1',
  `user_venue_coupon_title` varchar(100) DEFAULT NULL,
  `user_venue_coupon_desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `venue_coupon_id` (`venue_coupon_id`),
  CONSTRAINT `users_venues_coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_venues_coupons_ibfk_2` FOREIGN KEY (`venue_coupon_id`) REFERENCES `venues_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_coupons` */

/*Table structure for table `users_venues_follows` */

DROP TABLE IF EXISTS `users_venues_follows`;

CREATE TABLE `users_venues_follows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `user_venue_follow_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `users_venues_follows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_venues_follows_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_follows` */

/*Table structure for table `users_venues_ratings` */

DROP TABLE IF EXISTS `users_venues_ratings`;

CREATE TABLE `users_venues_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `venue_rating_cat_1` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Default: Service',
  `venue_rating_cat_2` int(1) DEFAULT '0' COMMENT 'Default: Staff',
  `venue_rating_cat_3` int(1) DEFAULT '0' COMMENT 'Default: Facility',
  `venue_rating_cat_4` int(1) DEFAULT '0' COMMENT 'Default: Custom 1',
  `venue_rating_cat_5` int(1) DEFAULT '0' COMMENT 'Default: Custom 2',
  `venue_rating_cat_6` int(1) DEFAULT '0' COMMENT 'Default: Custom 3',
  `venue_rating_average` decimal(3,2) DEFAULT '0.00',
  `venue_rating_comment` text,
  `venue_rating_date` datetime DEFAULT NULL,
  `venue_rating_acknowledged` tinyint(1) DEFAULT '0',
  `venue_rating_resolved` tinyint(1) DEFAULT '0',
  `venue_rating_date_resolved` datetime DEFAULT NULL,
  `venue_rating_resolve_expiration` date DEFAULT NULL,
  `venues_updated` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `users_venues_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_venues_ratings_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_ratings` */

/*Table structure for table `users_venues_ratings_images` */

DROP TABLE IF EXISTS `users_venues_ratings_images`;

CREATE TABLE `users_venues_ratings_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_venue_rating_id` int(11) DEFAULT NULL,
  `user_venue_rating_image_url` text,
  PRIMARY KEY (`id`),
  KEY `user_venue_rating_id` (`user_venue_rating_id`),
  CONSTRAINT `users_venues_ratings_images_ibfk_1` FOREIGN KEY (`user_venue_rating_id`) REFERENCES `users_venues_ratings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_ratings_images` */

/*Table structure for table `users_venues_ratings_responses` */

DROP TABLE IF EXISTS `users_venues_ratings_responses`;

CREATE TABLE `users_venues_ratings_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_venue_rating_id` int(11) DEFAULT NULL,
  `user_venue_rating_responding_user_id` int(11) DEFAULT NULL,
  `user_venue_rating_response` text,
  `user_venue_rating_response_date` datetime DEFAULT NULL,
  `user_venue_rating_response_read` tinyint(1) DEFAULT NULL,
  `user_venue_rating_response_read_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_venue_rating_id` (`user_venue_rating_id`),
  CONSTRAINT `users_venues_ratings_responses_ibfk_1` FOREIGN KEY (`user_venue_rating_id`) REFERENCES `users_venues_ratings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_ratings_responses` */

/*Table structure for table `venues` */

DROP TABLE IF EXISTS `venues`;

CREATE TABLE `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `venue_name` varchar(100) DEFAULT NULL,
  `venue_google_place_id` varchar(100) DEFAULT NULL,
  `venue_date_added` datetime DEFAULT NULL,
  `venue_date_modified` datetime DEFAULT NULL,
  `venue_address_1` varchar(100) DEFAULT NULL,
  `venue_address_2` varchar(100) DEFAULT NULL,
  `venue_city` varchar(20) DEFAULT NULL,
  `venue_state` char(2) DEFAULT NULL,
  `venue_zip` char(10) DEFAULT NULL,
  `venue_phone` varchar(16) DEFAULT NULL,
  `venue_email` varchar(100) DEFAULT NULL,
  `venue_website` text,
  `venue_lat` decimal(10,7) DEFAULT NULL,
  `venue_lon` decimal(10,7) DEFAULT NULL,
  `venue_claim_date` datetime DEFAULT NULL,
  `venue_claim_code` int(11) DEFAULT NULL,
  `venue_claim_code_exp` date DEFAULT NULL,
  `venue_claimed` tinyint(1) DEFAULT NULL,
  `venue_type_id` int(11) DEFAULT NULL,
  `venue_active` tinyint(1) DEFAULT NULL,
  `venue_verified` tinyint(4) DEFAULT '0',
  `venue_verified_date` datetime DEFAULT NULL,
  `venue_last_verified_date` datetime DEFAULT NULL,
  `venue_rating_avg` decimal(3,2) DEFAULT NULL,
  `venue_rating_percent` decimal(5,2) DEFAULT NULL,
  `venue_satisfaction_percent` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `venue_google_place_id` (`venue_google_place_id`),
  KEY `user_id` (`user_id`),
  KEY `venue_type_id` (`venue_type_id`),
  CONSTRAINT `venues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venues_ibfk_2` FOREIGN KEY (`venue_type_id`) REFERENCES `venues_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `venues` */

insert  into `venues`(`id`,`user_id`,`venue_name`,`venue_google_place_id`,`venue_date_added`,`venue_date_modified`,`venue_address_1`,`venue_address_2`,`venue_city`,`venue_state`,`venue_zip`,`venue_phone`,`venue_email`,`venue_website`,`venue_lat`,`venue_lon`,`venue_claim_date`,`venue_claim_code`,`venue_claim_code_exp`,`venue_claimed`,`venue_type_id`,`venue_active`,`venue_verified`,`venue_verified_date`,`venue_last_verified_date`,`venue_rating_avg`,`venue_rating_percent`,`venue_satisfaction_percent`) values (1,NULL,'Mountain View Hotel','ChIJXyyaSgi1RIYRrMCfHH8yvfw','2017-06-22 00:38:38',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=18211767989678620844','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,60,1,1,'2017-06-22 00:38:38','2017-06-22 00:38:38',NULL,NULL,NULL),(2,NULL,'Glyn and sandra meek','ChIJXyyaSgi1RIYRXSw4Kkqgy-s','2017-06-22 00:38:38',NULL,'',NULL,'Austin','TX','78701','(512) 750-0063',NULL,'https://maps.google.com/?cid=16990850259581676637','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,9,1,1,'2017-06-22 00:38:38','2017-06-22 00:38:38',NULL,NULL,NULL),(3,NULL,'Seacoas Bank','ChIJXyyaSgi1RIYRH_wSimkl2uM','2017-06-22 00:38:38',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=16418476526750858271','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,8,1,1,'2017-06-22 00:38:38','2017-06-22 00:38:38',NULL,NULL,NULL),(4,NULL,'Saint Mary Medical Center In Austin Texas','ChIJXyyaSgi1RIYREX_p8Sb5UtY','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=15443680017869537041','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,30,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(5,NULL,'La Fonda San Miguel','ChIJXyyaSgi1RIYRhu3RWlp3UNY','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=15442974352207900038','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,80,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(6,NULL,'Marriott','ChIJXyyaSgi1RIYRsJYBrZxKddQ','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=15309224545093785264','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,60,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(7,NULL,'Knife and fork','ChIJXyyaSgi1RIYRMe1YEMMLBco','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=14557054302965787953','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,9,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(8,NULL,'Food Bank','ChIJXyyaSgi1RIYRmTexWXmIJMU','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=14205629179410593689','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,89,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(9,NULL,'A To Z Translators, LLC','ChIJXyyaSgi1RIYRxSIdYb1n2sQ','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701','(512) 537-9098',NULL,'http://atoztranslators.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(10,NULL,'Texas Granite Group','ChIJXyyaSgi1RIYRjDL7Q7PZN7k','2017-06-22 00:38:39',NULL,'',NULL,'Austin','TX','78701','(512) 547-5712',NULL,'http://www.texasgranitegroup.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(11,NULL,'Sanora','ChIJXyyaSgi1RIYRPu_0SuuTn7M','2017-06-22 00:38:39',NULL,'501 Congress Avenue',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=12943226492870258494','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:39','2017-06-22 00:38:39',NULL,NULL,NULL),(12,NULL,'Good & Fair Clothing','ChIJGTddLt61RIYRR3HE_m1LorA','2017-06-22 00:38:40',NULL,'',NULL,'Austin','TX','78701','(512) 710-7722',NULL,'http://goodandfairclothing.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,25,1,1,'2017-06-22 00:38:40','2017-06-22 00:38:40',NULL,NULL,NULL),(13,NULL,'The Magical World Of Jeffrey Jester','ChIJXyyaSgi1RIYRuqskwfLWt6g','2017-06-22 00:38:40',NULL,'',NULL,'Austin','TX','78701','(512) 850-7736',NULL,'http://jeffreyjester.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:40','2017-06-22 00:38:40',NULL,NULL,NULL),(14,NULL,'One Ounce Opera','ChIJXyyaSgi1RIYRRDSkOpgYeJs','2017-06-22 00:38:40',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'http://oneounceopera.com/events/audition/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:40','2017-06-22 00:38:40',NULL,NULL,NULL),(15,NULL,'hka enterprises inc','ChIJXyyaSgi1RIYRIrkGJ7NAvJo','2017-06-22 00:38:40',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=11149857915660581154','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:40','2017-06-22 00:38:40',NULL,NULL,NULL),(16,NULL,'Genesis 7 Consulting, LLC','ChIJXyyaSgi1RIYRhThTv3giD5c','2017-06-22 00:38:40',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'http://genesis7consulting.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:40','2017-06-22 00:38:40',NULL,NULL,NULL),(17,NULL,'Hilton','ChIJXyyaSgi1RIYRMxSO1fHuHpI','2017-06-22 00:38:41',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'https://maps.google.com/?cid=10529115701276185651','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,60,1,1,'2017-06-22 00:38:41','2017-06-22 00:38:41',NULL,NULL,NULL),(18,NULL,'ZarZam Body Art & Face Painting','ChIJXyyaSgi1RIYRoFhWqOtVI44','2017-06-22 00:38:41',NULL,'',NULL,'Austin','TX','78701','(512) 736-9357',NULL,'http://www.zarzambodyart.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:41','2017-06-22 00:38:41',NULL,NULL,NULL),(19,NULL,'Rebecca Eller Photography','ChIJXyyaSgi1RIYRvz1F7UWAT3Y','2017-06-22 00:38:41',NULL,'',NULL,'Austin','TX','78701',NULL,NULL,'http://www.rebeccaellerphotography.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,NULL,1,1,'2017-06-22 00:38:41','2017-06-22 00:38:41',NULL,NULL,NULL),(20,NULL,'Ozarka Bottled Water Delivery Austin','ChIJXyyaSgi1RIYRJ_sw8jbEW28','2017-06-22 00:38:41',NULL,'',NULL,'Austin','TX','78782','(866) 889-3567',NULL,'http://delivery.ozarkawater.com/','30.2671530','-97.7430608',NULL,NULL,NULL,NULL,39,1,1,'2017-06-22 00:38:41','2017-06-22 00:38:41',NULL,NULL,NULL);

/*Table structure for table `venues_admins` */

DROP TABLE IF EXISTS `venues_admins`;

CREATE TABLE `venues_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `venue_admin_level` int(11) DEFAULT '1000' COMMENT '1000 is the highest level.',
  PRIMARY KEY (`id`),
  KEY `venue_id` (`venue_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `venues_admins_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venues_admins_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues_admins` */

/*Table structure for table `venues_coupons` */

DROP TABLE IF EXISTS `venues_coupons`;

CREATE TABLE `venues_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_id` int(11) DEFAULT NULL,
  `venue_admin_id` int(11) DEFAULT NULL,
  `coupon_name` varchar(50) DEFAULT NULL,
  `coupon_code` varchar(20) DEFAULT NULL,
  `coupon_use_limit` int(11) DEFAULT '1',
  `coupon_redeemed` tinyint(1) DEFAULT NULL,
  `coupon_redeem_date` datetime DEFAULT NULL,
  `coupon_expiration` date DEFAULT NULL,
  `coupon_created_date` datetime DEFAULT NULL,
  `coupon_modified_date` datetime DEFAULT NULL,
  `coupon_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venue_id` (`venue_id`),
  KEY `venue_admin_id` (`venue_admin_id`),
  CONSTRAINT `venues_coupons_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venues_coupons_ibfk_2` FOREIGN KEY (`venue_admin_id`) REFERENCES `venues_admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues_coupons` */

/*Table structure for table `venues_images` */

DROP TABLE IF EXISTS `venues_images`;

CREATE TABLE `venues_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_id` int(11) DEFAULT NULL,
  `venue_image_url` text,
  `venue_image_date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `venues_images_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `venues_images` */

insert  into `venues_images`(`id`,`venue_id`,`venue_image_url`,`venue_image_date_added`) values (1,4,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAANOZ4kLfxNV1TgLo0mMoQWjZ9QdEZxF3vg3K3YQ6m0bEdTawy5CKZ979XZLF7Md-U8NfBSHSa5LfzCmL1SW0nbbe-EEVVkQHWnm4zit-OXETaFPyVP8osR1t49RN3lOSSEhDY-ctZobx4xcm9GYMsxAS1GhQ6RBZHB_C-0O983Q8PX-IH-33bGQ&maxwidth=800','2017-06-22 00:38:39'),(2,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAMPqo_KZBwwtIy2mN1BAyBiri0ohQaU0TzdcZDQiJ_nYU-am2FRHFf0LFUfSXoeqnqkzviNFX1fHph3atiOb55vKNcl1W5zDIUjMNbb_yzNUSl8S95LLLmo4oXBLIOrJwEhAl8oOBCVGjDMkljfgIvyfwGhQ8KCqNjNthL3XppHvBC07140dvaw&maxwidth=800','2017-06-22 00:38:39'),(3,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAINJnJ3chWrWsrWNjMXrkTxTFqVKiKz4Y3DfaY_ZdunhJs0Nzz9w31s7V3Ylkx7QBN8T1HQcpT75C3JCqwuEGPJPowAIgw13SYGucnfBfEgK-zJBm51yFoSr54giMj4rdEhDKLEF0-p8KYC-ewumCsFjiGhQSi2x-oY2SAoqW5ftPIHplzYuSvw&maxwidth=800','2017-06-22 00:38:39'),(4,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAGA3eQW6vdW1gx64QMfKMXRMQfzti_Fk_4tQdkIb2MyE9NPjs3rxvIBGvPUX9ZVDthqYdFkb5uiRBpiFJKnjIBTPoIeF3XxBcZLAu6M16WyTrQPWqIDiOW7U99a23xswnEhAn7vE82b3TFi6ezeAcs6lTGhSuJ81FG5QTFCE4cYRIjJBVQ-U3IA&maxwidth=800','2017-06-22 00:38:39'),(5,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAA2cegssLgVvItSMRSerhMNJov51sgvRxrHiDOl2k-k1VyzTgqrXIsM_gquTtqc9iZNPsCidVB0z50-cGYs1Qgs-eJ9352JqOjRxYnaGcVMHzA2JH2PMpuAjDJZKJXPnK7EhBJ6AnzWiHassOvViIJbJylGhTjDlY9adzu5Zyvn7q3pVfNv22lJw&maxwidth=800','2017-06-22 00:38:39'),(6,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAny-5NUTICoChOfCGWrTuRLgUHzbDqCqlB0PFIFLxrso1q03M5hnWaCCY20pZp5g753OPLqE1UwMW2K1-FPeO44U92GT3g2Ttt4zAQwozJJPWXog9AmOfp4asA0FoDo_sEhBuAD941MMgAROQgfi7i7tSGhS1FSxB0N8VB3ZCIdggQt5gsoNBnA&maxwidth=800','2017-06-22 00:38:39'),(7,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAih5ODTTLIjl1kSkDdf0d1uxmT0hZof2tb2pMlg8cDbpOrrXj1nPUIJs9N8KYIPS-eXne5rCJ4g9Y7-ojmcMQOM2Xa9mGF8whYfMh2ereRXp0eOWo_650ob_DtF8hiPxjEhDGJYzDMpI6KWIXjplTBit1GhTAU0u2hNJYsurmCx5-y2O5imBMIw&maxwidth=800','2017-06-22 00:38:39'),(8,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAGGt2EiygX68AC2txNQg65QFIM40q9x3PL_OM7HrKqcyZiAOCUL717EduictrYG6Z28E9eItTaGDEBdvmKRBruw0mWeBvtAp-3FJzArwApoYWbVrvMFdueXVOnFxlkue0EhBiJM-FFeM4nY5b_GVQZDBiGhRc9yjB6HOn8OEN8hiKlppXLpUpdg&maxwidth=800','2017-06-22 00:38:39'),(9,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAApP2oqwaT-4tOaEWRDyRRZ0LqXb4wiU2d3d_mrRijRd-nidrb6ZYiyL0CMxtakd5U5dTfDsh73mbVgNl5WIm_RlVqYAafbR6U7318A6QswksQ0VNEesduK1EUcNU8YcZ0EhBwIwB296GBAyU_wCyo1elmGhSPgTajP9LkdelwekWH_usQDnEurw&maxwidth=800','2017-06-22 00:38:39'),(10,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAE4VR5Diw8Xeg8AK4hFY5vhaKhdrgy2K30N1Tt5lkCk0Hzdi_kKs6MDEKqJaAIQ8NvL4YZglx4-Zc3cDQvy7VJjnsdTfskwFWgS31CyGFqruhJB1umpq5tiYiWVL8bj07EhAF9Qe4LFNxlq8DGWfHu5kFGhShlNZdIIGG4lpCT-HwJRfe2H6ieA&maxwidth=800','2017-06-22 00:38:39'),(11,10,'https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyBNt6tgm-GSPdU4-sFtmD0o4mbckzTepVc&photoreference=CmRaAAAAB5T2A6t3ck2bhNECk6kTrT3G6Xf8lRE1uTtroiR2kx1_Ac7Ng90e3zZeXGYj9QcZ1m0ZSo9CMCQ7Re0E9s2D14Ah8v3zssp-_l2suwVz0De96uIy6DnVcWCRj4_QtIUfEhBsEKkPTENMzyU8lQIdBQsWGhSQ0kp14ZB_NOpBqyAcdkc-lTS1PA&maxwidth=800','2017-06-22 00:38:39');

/*Table structure for table `venues_settings` */

DROP TABLE IF EXISTS `venues_settings`;

CREATE TABLE `venues_settings` (
  `venue_id` int(11) NOT NULL,
  `venue_rating_category_mapping` text,
  `venue_rating_resolve_exp_days` int(3) DEFAULT '31',
  UNIQUE KEY `venue_id` (`venue_id`),
  CONSTRAINT `venues_settings_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues_settings` */

insert  into `venues_settings`(`venue_id`,`venue_rating_category_mapping`,`venue_rating_resolve_exp_days`) values (1,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(2,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(3,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(4,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(5,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(6,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(7,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(8,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(9,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(10,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(11,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(12,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(13,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(14,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(15,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(16,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(17,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(18,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(19,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31),(20,'{\"venue_rating_cat_1\":\"Service\",\"venue_rating_cat_2\":\"Staff\",\"venue_rating_cat_3\":\"Facility\",\"venue_rating_cat_4\":\"Custom 1\",\"venue_rating_cat_5\":\"Custom 2\",\"venue_rating_cat_6\":\"Custom 3\"}',31);

/*Table structure for table `venues_types` */

DROP TABLE IF EXISTS `venues_types`;

CREATE TABLE `venues_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_type_slug` varchar(20) DEFAULT NULL,
  `venue_type_name` varchar(100) DEFAULT NULL,
  `venue_type_description` varchar(255) DEFAULT NULL,
  `venue_type_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;

/*Data for the table `venues_types` */

insert  into `venues_types`(`id`,`venue_type_slug`,`venue_type_name`,`venue_type_description`,`venue_type_active`) values (1,'accounting','Accounting',NULL,1),(2,'art_gallery','Art Gallery',NULL,1),(3,'aquarium','Aquarium',NULL,1),(4,'airport','Airport',NULL,1),(5,'amusement_park','Amusement Park',NULL,1),(6,'atm','Atm',NULL,1),(7,'bakery','Bakery',NULL,1),(8,'bank','Bank',NULL,1),(9,'bar','Bar',NULL,1),(10,'beauty_salon','Beauty Salon',NULL,1),(11,'bicycle_store','Bicycle Store',NULL,1),(12,'book_store','Book Store',NULL,1),(13,'bowling_alley','Bowling Alley',NULL,1),(14,'bus_station','Bus Station',NULL,1),(15,'cafe','Cafe',NULL,1),(16,'campground','Campground',NULL,1),(17,'car_dealer','Car Dealer',NULL,1),(18,'car_rental','Car Rental',NULL,1),(19,'car_repair','Car Repair',NULL,1),(20,'car_wash','Car Wash',NULL,1),(21,'casino','Casino',NULL,1),(22,'cemetery','Cemetery',NULL,1),(23,'church','Church',NULL,1),(24,'city_hall','City Hall',NULL,1),(25,'clothing_store','Clothing Store',NULL,1),(26,'convenience_store','Convenience Store',NULL,1),(27,'courthouse','Courthouse',NULL,1),(28,'dentist','Dentist',NULL,1),(29,'department_store','Department Store',NULL,1),(30,'doctor','Doctor',NULL,1),(31,'electrician','Electrician',NULL,1),(32,'electronics_store','Electronics Store',NULL,1),(33,'establishment','Establishment',NULL,1),(34,'embassy','Embassy',NULL,1),(35,'fire_station','Fire Station',NULL,1),(36,'florist','Florist',NULL,1),(37,'funeral_home','Funeral Home',NULL,1),(38,'furniture_store','Furniture Store',NULL,1),(39,'food','Food',NULL,1),(40,'finance','Finance',NULL,1),(41,'gas_station','Gas Station',NULL,1),(42,'grocery','Grocery',NULL,1),(43,'general_contractor','General Contractor',NULL,1),(44,'grocery_or_supermark','Grocery Or Supermarket',NULL,1),(45,'gym','Gym',NULL,1),(46,'hair_care','Hair Care',NULL,1),(47,'hardware_store','Hardware Store',NULL,1),(48,'hindu_temple','Hindu Temple',NULL,1),(49,'home_goods_store','Home Goods Store',NULL,1),(50,'hospital','Hospital',NULL,1),(51,'health','Health',NULL,1),(52,'insurance_agency','Insurance Agency',NULL,1),(53,'jewelry_store','Jewelry Store',NULL,1),(54,'laundry','Laundry',NULL,1),(55,'lawyer','Lawyer',NULL,1),(56,'library','Library',NULL,1),(57,'liquor_store','Liquor Store',NULL,1),(58,'local_government_off','Local Government Office',NULL,1),(59,'locksmith','Locksmith',NULL,1),(60,'lodging','Lodging',NULL,1),(61,'meal_delivery','Meal Delivery',NULL,1),(62,'meal_takeaway','Meal Takeaway',NULL,1),(63,'mosque','Mosque',NULL,1),(64,'movie_rental','Movie Rental',NULL,1),(65,'movie_theater','Movie Theater',NULL,1),(66,'moving_company','Moving Company',NULL,1),(67,'museum','Museum',NULL,1),(68,'night_club','Night Club',NULL,1),(69,'painter','Painter',NULL,1),(70,'park','Park',NULL,1),(71,'parking','Parking',NULL,1),(72,'pet_store','Pet Store',NULL,1),(73,'pharmacy','Pharmacy',NULL,1),(74,'physiotherapist','Physiotherapist',NULL,1),(75,'plumber','Plumber',NULL,1),(76,'police','Police',NULL,1),(77,'post_office','Post Office',NULL,1),(78,'place_of_worship','Place Of Worship',NULL,1),(79,'real_estate_agency','Real Estate Agency',NULL,1),(80,'restaurant','Restaurant',NULL,1),(81,'roofing_contractor','Roofing Contractor',NULL,1),(82,'rv_park','Rv Park',NULL,1),(83,'school','School',NULL,1),(84,'shoe_store','Shoe Store',NULL,1),(85,'shopping_mall','Shopping Mall',NULL,1),(86,'spa','Spa',NULL,1),(87,'stadium','Stadium',NULL,1),(88,'storage','Storage',NULL,1),(89,'store','Store',NULL,1),(90,'subway_station','Subway Station',NULL,1),(91,'synagogue','Synagogue',NULL,1),(92,'taxi_stand','Taxi Stand',NULL,1),(93,'train_station','Train Station',NULL,1),(94,'transit_station','Transit Station',NULL,1),(95,'travel_agency','Travel Agency',NULL,1),(96,'university','University',NULL,1),(97,'veterinary_care','Veterinary Care',NULL,1),(98,'zoo','Zoo',NULL,1);

/* Trigger structure for table `users_venues_ratings` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `users_venues_ratings_on_insert` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `users_venues_ratings_on_insert` AFTER INSERT ON `users_venues_ratings` FOR EACH ROW BEGIN
	call venues_update_satisfaction_stats(NEW.venue_id);
    END */$$


DELIMITER ;

/* Trigger structure for table `users_venues_ratings` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `users_venues_ratings_on_update` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `users_venues_ratings_on_update` AFTER UPDATE ON `users_venues_ratings` FOR EACH ROW BEGIN
	call venues_update_satisfaction_stats(NEW.venue_id);
    END */$$


DELIMITER ;

/* Trigger structure for table `users_venues_ratings` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `users_venues_ratings_on_delete` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `users_venues_ratings_on_delete` AFTER DELETE ON `users_venues_ratings` FOR EACH ROW BEGIN
	call venues_update_satisfaction_stats(OLD.venue_id);
    END */$$


DELIMITER ;

/* Trigger structure for table `venues` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `venues_settings__add_default_values__on_insert` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `venues_settings__add_default_values__on_insert` AFTER INSERT ON `venues` FOR EACH ROW BEGIN
	INSERT INTO venues_settings (venue_id,venue_rating_category_mapping) VALUES (NEW.id,'{"venue_rating_cat_1":"Service","venue_rating_cat_2":"Staff","venue_rating_cat_3":"Facility","venue_rating_cat_4":"Custom 1","venue_rating_cat_5":"Custom 2","venue_rating_cat_6":"Custom 3"}');
    END */$$


DELIMITER ;

/* Function  structure for function  `fx_venues_total_ratings` */

/*!50003 DROP FUNCTION IF EXISTS `fx_venues_total_ratings` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `fx_venues_total_ratings`(vid int) RETURNS int(32)
BEGIN
	declare total_ratings int(32);
	SELECT COUNT(*) INTO total_ratings FROM users_venues_ratings vr WHERE vr.venue_id = vid;
	return total_ratings;
    END */$$
DELIMITER ;

/* Function  structure for function  `fx_venues_total_resolved_ratings` */

/*!50003 DROP FUNCTION IF EXISTS `fx_venues_total_resolved_ratings` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `fx_venues_total_resolved_ratings`(vid int) RETURNS int(32)
BEGIN
	declare total_resolved_ratings int(32);
	SELECT COUNT(*) INTO total_resolved_ratings FROM users_venues_ratings vr WHERE vr.venue_id = vid and venue_rating_resolved is true;
	return total_resolved_ratings;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `venues_update_satisfaction_stats` */

/*!50003 DROP PROCEDURE IF EXISTS  `venues_update_satisfaction_stats` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `venues_update_satisfaction_stats`(vid INT)
BEGIN
    DECLARE venue_rating_avg_adjusted DECIMAL(3,2);
    declare venue_total_ratings int(11);
    declare venue_total_resolved_ratings int(11);
    declare venue_resolved_percent decimal(5,2);
    declare venue_rating_percent_adjusted decimal(5,2);
    declare venue_satisfaction decimal(5,2);
    select AVG(`venue_rating_average`) into venue_rating_avg_adjusted FROM users_venues_ratings vr where vr.venue_id = vid;
    select fx_venues_total_ratings(vid) into venue_total_ratings;
    SELECT fx_venues_total_resolved_ratings(vid) INTO venue_total_resolved_ratings;
    set venue_resolved_percent = (venue_total_resolved_ratings/venue_total_ratings) * 100;
    set venue_rating_percent_adjusted = (venue_rating_avg_adjusted/5)*100;
    set venue_satisfaction = (venue_resolved_percent * 0.2) + (venue_rating_percent_adjusted * 0.8);
    UPDATE venues SET
    `venue_rating_avg` = venue_rating_avg_adjusted,
    `venue_rating_percent` = venue_rating_percent_adjusted,
    `venue_satisfaction_percent` = venue_satisfaction
    where id = vid;
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
