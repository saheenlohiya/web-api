/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tellus_tests` /*!40100 DEFAULT CHARACTER SET utf8 */;

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
  `user_device_token` varchar(255) DEFAULT NULL,
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
  `venue_claim_claimer_name` varchar(255) DEFAULT NULL,
  `venue_claim_claimer_email` varchar(100) DEFAULT NULL,
  `venue_claim_claimer_phone` varchar(20) DEFAULT NULL,
  `venue_claim_date` datetime DEFAULT NULL,
  `venue_claim_status` enum('pending','active','suspended') DEFAULT 'pending',
  `venue_claim_verified_date` datetime DEFAULT NULL,
  `venue_claim_verify_admin` int(11) DEFAULT NULL,
  `venue_claim_hash` varchar(50) DEFAULT NULL,
  `venue_claim_code` int(11) DEFAULT NULL,
  `venue_claim_update_date` datetime DEFAULT NULL,
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
  `venue_rating_acknowledged_date` datetime DEFAULT NULL,
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
  KEY `user_venue_rating_responding_user_id` (`user_venue_rating_responding_user_id`),
  CONSTRAINT `users_venues_ratings_responses_ibfk_1` FOREIGN KEY (`user_venue_rating_id`) REFERENCES `users_venues_ratings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_venues_ratings_responses_ibfk_2` FOREIGN KEY (`user_venue_rating_responding_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `venues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `venues_ibfk_2` FOREIGN KEY (`venue_type_id`) REFERENCES `venues_types` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues` */

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues_images` */

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

insert  into `venues_types`(`id`,`venue_type_slug`,`venue_type_name`,`venue_type_description`,`venue_type_active`) values 
(1,'accounting','Accounting',NULL,1),
(2,'art_gallery','Art Gallery',NULL,1),
(3,'aquarium','Aquarium',NULL,1),
(4,'airport','Airport',NULL,1),
(5,'amusement_park','Amusement Park',NULL,1),
(6,'atm','Atm',NULL,1),
(7,'bakery','Bakery',NULL,1),
(8,'bank','Bank',NULL,1),
(9,'bar','Bar',NULL,1),
(10,'beauty_salon','Beauty Salon',NULL,1),
(11,'bicycle_store','Bicycle Store',NULL,1),
(12,'book_store','Book Store',NULL,1),
(13,'bowling_alley','Bowling Alley',NULL,1),
(14,'bus_station','Bus Station',NULL,1),
(15,'cafe','Cafe',NULL,1),
(16,'campground','Campground',NULL,1),
(17,'car_dealer','Car Dealer',NULL,1),
(18,'car_rental','Car Rental',NULL,1),
(19,'car_repair','Car Repair',NULL,1),
(20,'car_wash','Car Wash',NULL,1),
(21,'casino','Casino',NULL,1),
(22,'cemetery','Cemetery',NULL,1),
(23,'church','Church',NULL,1),
(24,'city_hall','City Hall',NULL,1),
(25,'clothing_store','Clothing Store',NULL,1),
(26,'convenience_store','Convenience Store',NULL,1),
(27,'courthouse','Courthouse',NULL,1),
(28,'dentist','Dentist',NULL,1),
(29,'department_store','Department Store',NULL,1),
(30,'doctor','Doctor',NULL,1),
(31,'electrician','Electrician',NULL,1),
(32,'electronics_store','Electronics Store',NULL,1),
(33,'establishment','Establishment',NULL,1),
(34,'embassy','Embassy',NULL,1),
(35,'fire_station','Fire Station',NULL,1),
(36,'florist','Florist',NULL,1),
(37,'funeral_home','Funeral Home',NULL,1),
(38,'furniture_store','Furniture Store',NULL,1),
(39,'food','Food',NULL,1),
(40,'finance','Finance',NULL,1),
(41,'gas_station','Gas Station',NULL,1),
(42,'grocery','Grocery',NULL,1),
(43,'general_contractor','General Contractor',NULL,1),
(44,'grocery_or_supermark','Grocery Or Supermarket',NULL,1),
(45,'gym','Gym',NULL,1),
(46,'hair_care','Hair Care',NULL,1),
(47,'hardware_store','Hardware Store',NULL,1),
(48,'hindu_temple','Hindu Temple',NULL,1),
(49,'home_goods_store','Home Goods Store',NULL,1),
(50,'hospital','Hospital',NULL,1),
(51,'health','Health',NULL,1),
(52,'insurance_agency','Insurance Agency',NULL,1),
(53,'jewelry_store','Jewelry Store',NULL,1),
(54,'laundry','Laundry',NULL,1),
(55,'lawyer','Lawyer',NULL,1),
(56,'library','Library',NULL,1),
(57,'liquor_store','Liquor Store',NULL,1),
(58,'local_government_off','Local Government Office',NULL,1),
(59,'locksmith','Locksmith',NULL,1),
(60,'lodging','Lodging',NULL,1),
(61,'meal_delivery','Meal Delivery',NULL,1),
(62,'meal_takeaway','Meal Takeaway',NULL,1),
(63,'mosque','Mosque',NULL,1),
(64,'movie_rental','Movie Rental',NULL,1),
(65,'movie_theater','Movie Theater',NULL,1),
(66,'moving_company','Moving Company',NULL,1),
(67,'museum','Museum',NULL,1),
(68,'night_club','Night Club',NULL,1),
(69,'painter','Painter',NULL,1),
(70,'park','Park',NULL,1),
(71,'parking','Parking',NULL,1),
(72,'pet_store','Pet Store',NULL,1),
(73,'pharmacy','Pharmacy',NULL,1),
(74,'physiotherapist','Physiotherapist',NULL,1),
(75,'plumber','Plumber',NULL,1),
(76,'police','Police',NULL,1),
(77,'post_office','Post Office',NULL,1),
(78,'place_of_worship','Place Of Worship',NULL,1),
(79,'real_estate_agency','Real Estate Agency',NULL,1),
(80,'restaurant','Restaurant',NULL,1),
(81,'roofing_contractor','Roofing Contractor',NULL,1),
(82,'rv_park','Rv Park',NULL,1),
(83,'school','School',NULL,1),
(84,'shoe_store','Shoe Store',NULL,1),
(85,'shopping_mall','Shopping Mall',NULL,1),
(86,'spa','Spa',NULL,1),
(87,'stadium','Stadium',NULL,1),
(88,'storage','Storage',NULL,1),
(89,'store','Store',NULL,1),
(90,'subway_station','Subway Station',NULL,1),
(91,'synagogue','Synagogue',NULL,1),
(92,'taxi_stand','Taxi Stand',NULL,1),
(93,'train_station','Train Station',NULL,1),
(94,'transit_station','Transit Station',NULL,1),
(95,'travel_agency','Travel Agency',NULL,1),
(96,'university','University',NULL,1),
(97,'veterinary_care','Veterinary Care',NULL,1),
(98,'zoo','Zoo',NULL,1);

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
	SELECT COUNT(*) INTO total_ratings FROM users_venues_ratings vr 
	WHERE vr.venue_id = vid and ((CURRENT_DATE() >= vr.venue_rating_resolve_expiration) OR vr.venue_rating_resolved IS TRUE);
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
    select AVG(`venue_rating_average`) into venue_rating_avg_adjusted FROM users_venues_ratings vr where vr.venue_id = vid AND ((CURRENT_DATE() >= vr.venue_rating_resolve_expiration) OR vr.venue_rating_resolved IS TRUE);
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
