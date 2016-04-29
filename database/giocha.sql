/*
Navicat MySQL Data Transfer

Source Server         : localhsot
Source Server Version : 50549
Source Host           : localhost:3306
Source Database       : giocha

Target Server Type    : MYSQL
Target Server Version : 50549
File Encoding         : 65001

Date: 2016-04-29 09:32:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for daily_transactions
-- ----------------------------
DROP TABLE IF EXISTS `daily_transactions`;
CREATE TABLE `daily_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_time` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of daily_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for daily_transactions_products
-- ----------------------------
DROP TABLE IF EXISTS `daily_transactions_products`;
CREATE TABLE `daily_transactions_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `daily_transaction_id` int(10) unsigned NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `daily_transactions_products_product_id_foreign` (`product_id`),
  KEY `daily_transactions_products_daily_transaction_id_foreign` (`daily_transaction_id`),
  CONSTRAINT `daily_transactions_products_daily_transaction_id_foreign` FOREIGN KEY (`daily_transaction_id`) REFERENCES `daily_transactions` (`id`),
  CONSTRAINT `daily_transactions_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of daily_transactions_products
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('2016_03_06_135035_create_daily_transactions_table', '1');
INSERT INTO `migrations` VALUES ('2016_03_06_135100_create_products_table', '1');
INSERT INTO `migrations` VALUES ('2016_03_06_135126_create_orders_table', '1');
INSERT INTO `migrations` VALUES ('2016_03_06_135402_create_daily_transactions_products_table', '1');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `daily_transaction_id` int(10) unsigned NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_daily_transaction_id_foreign` (`daily_transaction_id`),
  CONSTRAINT `orders_daily_transaction_id_foreign` FOREIGN KEY (`daily_transaction_id`) REFERENCES `daily_transactions` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `creator` int(10) unsigned NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `products_creator_foreign` (`creator`),
  CONSTRAINT `products_creator_foreign` FOREIGN KEY (`creator`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'Giò chả', '10.5', 'Giò chả không phụ gia', '1', '1461801430', '1461801430');
INSERT INTO `products` VALUES ('2', 'Bánh ', '12.5', 'Bánh mỳ kẹp giò ', '1', '1461801430', '1461801430');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `role` tinyint(4) DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Dean Chenkie', 'dean@gmail.com', '$2y$10$JaXwQit06Uu9jvWAzUuJPua.crAom2dH8sKVcDZ6CLLQgt0rDi7iG', null, '1461801430', '1461801430', '2');
INSERT INTO `users` VALUES ('2', 'Sean Parker', 'sean@gmail.com', '$2y$10$AjedtT1eRIt9kgRh2DfpkuzScCg0Cszv4/OmZES6z/VIB4Pa/WyVO', null, '1461801430', '1461801430', '2');
INSERT INTO `users` VALUES ('3', 'Ken Jake', 'ken@gmail.com', '$2y$10$W.9nTVEYhuSdhQ6YoKYBq.1t.SaCZPOpJUTInzllN5Wo/kJSxqFJ6', null, '1461801430', '1461801430', '2');
INSERT INTO `users` VALUES ('4', 'Andrew Ho', 'andrew@gmail.com', '$2y$10$PagG5gST/rh6tzupRDGWOOW7W8vV.h61ewAFCCHiJGDDE7C2k3A2S', null, '1461801430', '1461801430', '2');
INSERT INTO `users` VALUES ('5', 'Steve Nguyen ', 'steve@gmail.com', '$2y$10$WozSttgA8PrdqHGHcGmPDOsbCOzVc095pGNw2/uPAoQwUMtslxt7G', null, '1461801430', '1461801430', '2');
