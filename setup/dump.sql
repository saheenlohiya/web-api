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

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) DEFAULT NULL,
  `user_firstname` varchar(50) DEFAULT NULL,
  `user_lastname` varchar(50) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
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
  UNIQUE KEY `user_email` (`user_email`,`user_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users` */

/*Table structure for table `users_venues_coupons` */

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

CREATE TABLE `users_venues_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `venue_rating` int(1) unsigned NOT NULL DEFAULT '5',
  `venue_rating_comment` text,
  `venue_rating_date` datetime DEFAULT NULL,
  `venue_rating_acknowledged` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `users_venues_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_venues_ratings_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `users_venues_ratings` */

/*Table structure for table `users_venues_ratings_images` */

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
  `venue_lat` decimal(10,7) DEFAULT NULL,
  `venue_lon` decimal(10,7) DEFAULT NULL,
  `venue_claim_date` datetime DEFAULT NULL,
  `venue_claim_code` int(11) DEFAULT NULL,
  `venue_claim_code_exp` date DEFAULT NULL,
  `venue_claimed` tinyint(1) DEFAULT NULL,
  `venue_type_id` int(11) DEFAULT NULL,
  `venue_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `venue_google_place_id` (`venue_google_place_id`),
  KEY `user_id` (`user_id`),
  KEY `venue_type_id` (`venue_type_id`),
  CONSTRAINT `venues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venues_ibfk_2` FOREIGN KEY (`venue_type_id`) REFERENCES `venues_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues` */

/*Table structure for table `venues_admins` */

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

CREATE TABLE `venues_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_id` int(11) DEFAULT NULL,
  `venue_image_url` text,
  `venue_image_date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `venues_images` */

/*Table structure for table `venues_types` */

CREATE TABLE `venues_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_type_slug` varchar(20) DEFAULT NULL,
  `venue_type_name` varchar(100) DEFAULT NULL,
  `venue_type_description` varchar(255) DEFAULT NULL,
  `venue_type_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;

/*Data for the table `venues_types` */

insert  into `venues_types`(`id`,`venue_type_slug`,`venue_type_name`,`venue_type_description`,`venue_type_active`) values (1,'accounting','Accounting',NULL,NULL),(2,'art_gallery','Art Gallery',NULL,NULL),(3,'aquarium','Aquarium',NULL,NULL),(4,'airport','Airport',NULL,NULL),(5,'amusement_park','Amusement Park',NULL,NULL),(6,'atm','Atm',NULL,NULL),(7,'bakery','Bakery',NULL,NULL),(8,'bank','Bank',NULL,NULL),(9,'bar','Bar',NULL,NULL),(10,'beauty_salon','Beauty Salon',NULL,NULL),(11,'bicycle_store','Bicycle Store',NULL,NULL),(12,'book_store','Book Store',NULL,NULL),(13,'bowling_alley','Bowling Alley',NULL,NULL),(14,'bus_station','Bus Station',NULL,NULL),(15,'cafe','Cafe',NULL,NULL),(16,'campground','Campground',NULL,NULL),(17,'car_dealer','Car Dealer',NULL,NULL),(18,'car_rental','Car Rental',NULL,NULL),(19,'car_repair','Car Repair',NULL,NULL),(20,'car_wash','Car Wash',NULL,NULL),(21,'casino','Casino',NULL,NULL),(22,'cemetery','Cemetery',NULL,NULL),(23,'church','Church',NULL,NULL),(24,'city_hall','City Hall',NULL,NULL),(25,'clothing_store','Clothing Store',NULL,NULL),(26,'convenience_store','Convenience Store',NULL,NULL),(27,'courthouse','Courthouse',NULL,NULL),(28,'dentist','Dentist',NULL,NULL),(29,'department_store','Department Store',NULL,NULL),(30,'doctor','Doctor',NULL,NULL),(31,'electrician','Electrician',NULL,NULL),(32,'electronics_store','Electronics Store',NULL,NULL),(33,'embassy','Embassy',NULL,NULL),(34,'fire_station','Fire Station',NULL,NULL),(35,'florist','Florist',NULL,NULL),(36,'funeral_home','Funeral Home',NULL,NULL),(37,'furniture_store','Furniture Store',NULL,NULL),(38,'gas_station','Gas Station',NULL,NULL),(39,'grocery','Grocery',NULL,NULL),(40,'gym','Gym',NULL,NULL),(41,'hair_care','Hair Care',NULL,NULL),(42,'hardware_store','Hardware Store',NULL,NULL),(43,'hindu_temple','Hindu Temple',NULL,NULL),(44,'home_goods_store','Home Goods Store',NULL,NULL),(45,'hospital','Hospital',NULL,NULL),(46,'insurance_agency','Insurance Agency',NULL,NULL),(47,'jewelry_store','Jewelry Store',NULL,NULL),(48,'laundry','Laundry',NULL,NULL),(49,'lawyer','Lawyer',NULL,NULL),(50,'library','Library',NULL,NULL),(51,'liquor_store','Liquor Store',NULL,NULL),(52,'local_government_off','Local Government Office',NULL,NULL),(53,'locksmith','Locksmith',NULL,NULL),(54,'lodging','Lodging',NULL,NULL),(55,'meal_delivery','Meal Delivery',NULL,NULL),(56,'meal_takeaway','Meal Takeaway',NULL,NULL),(57,'mosque','Mosque',NULL,NULL),(58,'movie_rental','Movie Rental',NULL,NULL),(59,'movie_theater','Movie Theater',NULL,NULL),(60,'moving_company','Moving Company',NULL,NULL),(61,'museum','Museum',NULL,NULL),(62,'night_club','Night Club',NULL,NULL),(63,'painter','Painter',NULL,NULL),(64,'park','Park',NULL,NULL),(65,'parking','Parking',NULL,NULL),(66,'pet_store','Pet Store',NULL,NULL),(67,'pharmacy','Pharmacy',NULL,NULL),(68,'physiotherapist','Physiotherapist',NULL,NULL),(69,'plumber','Plumber',NULL,NULL),(70,'police','Police',NULL,NULL),(71,'post_office','Post Office',NULL,NULL),(72,'real_estate_agency','Real Estate Agency',NULL,NULL),(73,'restaurant','Restaurant',NULL,NULL),(74,'roofing_contractor','Roofing Contractor',NULL,NULL),(75,'rv_park','Rv Park',NULL,NULL),(76,'school','School',NULL,NULL),(77,'shoe_store','Shoe Store',NULL,NULL),(78,'shopping_mall','Shopping Mall',NULL,NULL),(79,'spa','Spa',NULL,NULL),(80,'stadium','Stadium',NULL,NULL),(81,'storage','Storage',NULL,NULL),(82,'store','Store',NULL,NULL),(83,'subway_station','Subway Station',NULL,NULL),(84,'synagogue','Synagogue',NULL,NULL),(85,'taxi_stand','Taxi Stand',NULL,NULL),(86,'train_station','Train Station',NULL,NULL),(87,'transit_station','Transit Station',NULL,NULL),(88,'travel_agency','Travel Agency',NULL,NULL),(89,'university','University',NULL,NULL),(90,'veterinary_care','Veterinary Care',NULL,NULL),(91,'zoo','Zoo',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
