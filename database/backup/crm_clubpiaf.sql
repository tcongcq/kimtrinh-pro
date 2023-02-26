/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80026
 Source Host           : localhost:3306
 Source Schema         : crm_clubpiaf

 Target Server Type    : MySQL
 Target Server Version : 80026
 File Encoding         : 65001

 Date: 10/10/2021 18:14:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for attachments
-- ----------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `src` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `thumbnail_src` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_extension` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `important` tinyint NOT NULL DEFAULT '0',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`),
  CONSTRAINT `attachments_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of attachments
-- ----------------------------
BEGIN;
INSERT INTO `attachments` VALUES (1, NULL, 'demo', NULL, NULL, NULL, 1, 1, NULL, 0, NULL, '2021-07-29 21:10:31', '2021-07-31 14:10:42');
COMMIT;

-- ----------------------------
-- Table structure for customer_contacts
-- ----------------------------
DROP TABLE IF EXISTS `customer_contacts`;
CREATE TABLE `customer_contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'thương hiệu/công ty',
  `field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'ngành hàng',
  `demand` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `concern` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `user_sale_id` int unsigned NOT NULL DEFAULT '1',
  `active` tinyint NOT NULL DEFAULT '0',
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `customer_contacts_ibfk_1` (`customer_id`),
  KEY `user_created_id` (`user_created_id`),
  KEY `user_sale_id` (`user_sale_id`),
  CONSTRAINT `customer_contacts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `customer_contacts_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`),
  CONSTRAINT `customer_contacts_ibfk_3` FOREIGN KEY (`user_sale_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customer_contacts
-- ----------------------------
BEGIN;
INSERT INTO `customer_contacts` VALUES (1, 'example', '0900009009', NULL, 'abc', NULL, 'a', NULL, NULL, 1, 1, 1, 0, NULL, '2021-09-25 23:41:56', '2021-09-25 23:41:56');
COMMIT;

-- ----------------------------
-- Table structure for customer_messages
-- ----------------------------
DROP TABLE IF EXISTS `customer_messages`;
CREATE TABLE `customer_messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `event_id` (`customer_id`) USING BTREE,
  KEY `user_id` (`user_created_id`) USING BTREE,
  CONSTRAINT `customer_messages_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_messages_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customer_messages
-- ----------------------------
BEGIN;
INSERT INTO `customer_messages` VALUES (1, NULL, NULL, 'demo', NULL, 1, 1, '2021-07-29 21:15:04', '2021-07-29 21:15:04');
COMMIT;

-- ----------------------------
-- Table structure for customer_plans
-- ----------------------------
DROP TABLE IF EXISTS `customer_plans`;
CREATE TABLE `customer_plans` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emotion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `customer_id` int unsigned NOT NULL DEFAULT '1' COMMENT ' ',
  `user_created_id` int NOT NULL DEFAULT '1',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `active` tinyint NOT NULL DEFAULT '0',
  `finished` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `customer_plans_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customer_plans
-- ----------------------------
BEGIN;
INSERT INTO `customer_plans` VALUES (1, 'demo', NULL, NULL, 1, 1, NULL, 0, 0, '2021-07-29 21:14:09', '2021-07-29 21:14:09');
COMMIT;

-- ----------------------------
-- Table structure for customer_products
-- ----------------------------
DROP TABLE IF EXISTS `customer_products`;
CREATE TABLE `customer_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `customer_id` (`customer_id`),
  KEY `user_created_id` (`user_created_id`),
  CONSTRAINT `customer_products_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_products_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customer_products
-- ----------------------------
BEGIN;
INSERT INTO `customer_products` VALUES (1, NULL, 'demo', NULL, 1, 1, NULL, 0, '2021-07-29 21:07:30', '2021-09-25 23:01:41');
COMMIT;

-- ----------------------------
-- Table structure for customer_reminders
-- ----------------------------
DROP TABLE IF EXISTS `customer_reminders`;
CREATE TABLE `customer_reminders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remind_at` datetime DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `reminder_user_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `approveed` tinyint NOT NULL DEFAULT '0',
  `approved_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `approved_time` datetime DEFAULT NULL,
  `priority` tinyint NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reminder_user_id` (`reminder_user_id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`) USING BTREE,
  KEY `customer_id` (`customer_id`) USING BTREE,
  CONSTRAINT `customer_reminders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_reminders_ibfk_2` FOREIGN KEY (`reminder_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_reminders_ibfk_3` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customer_reminders
-- ----------------------------
BEGIN;
INSERT INTO `customer_reminders` VALUES (1, 'demo', NULL, NULL, NULL, 1, 1, 1, 0, NULL, NULL, 0, 0, NULL, '2021-07-29 21:13:21', '2021-07-29 21:13:21');
COMMIT;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'chức vụ',
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'tên công ty',
  `brand_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'tên thương hiệu',
  `company_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ngành hàng',
  `branch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Thể loại',
  `active` tinyint DEFAULT '1',
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'DRAFT' COMMENT 'Tình trạng khách hàng',
  `contract_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái hợp đồng',
  `payment_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái thanh toán',
  `sign_date` datetime DEFAULT NULL COMMENT 'Ngày ký hợp đồng',
  `describe` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả khách hàng',
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nguồn khai thác',
  `demand` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Nhu cầu',
  `concern` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Lo lắng',
  `user_assigned_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `upload_dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state_changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`) USING BTREE,
  KEY `user_assigned_id` (`user_assigned_id`) USING BTREE,
  CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`),
  CONSTRAINT `customers_ibfk_3` FOREIGN KEY (`user_assigned_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customers
-- ----------------------------
BEGIN;
INSERT INTO `customers` VALUES (1, NULL, 'abc', NULL, 'demo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'DRAFT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2021-07-29 21:07:34', '2021-09-25 22:35:18', '2021-10-06 23:26:49', '2021-07-29 21:07:34');
COMMIT;

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `default` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of languages
-- ----------------------------
BEGIN;
INSERT INTO `languages` VALUES (1, 'demo', 0, '2017-02-25 20:50:18', '2021-07-31 14:11:20');
INSERT INTO `languages` VALUES (2, 'vi', 1, '2017-02-25 20:50:18', '2021-07-31 14:11:11');
INSERT INTO `languages` VALUES (3, 'en', 0, '2021-07-31 14:11:01', '2021-07-31 14:11:01');
COMMIT;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
BEGIN;
INSERT INTO `password_resets` VALUES (1, 'demo', '', '2021-07-31 14:11:31', '2021-07-31 14:11:31');
COMMIT;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` VALUES (1, 'demo', NULL, 'demo', NULL, NULL, '2021-07-31 14:11:41', '2021-07-31 14:11:41');
INSERT INTO `permissions` VALUES (2, 'access', NULL, 'customer-reminder', NULL, NULL, '2021-10-10 21:41:36', '2021-10-10 21:41:36');
INSERT INTO `permissions` VALUES (3, 'access-all', NULL, 'customer-reminder', NULL, NULL, '2021-10-10 21:50:27', '2021-10-10 21:50:27');
INSERT INTO `permissions` VALUES (4, 'change-date', NULL, 'customer-reminder', NULL, NULL, '2021-10-10 21:54:02', '2021-10-10 21:54:02');
INSERT INTO `permissions` VALUES (5, 'access', NULL, 'customer-reminder-report', NULL, NULL, '2021-10-10 21:41:36', '2021-10-10 21:56:37');
INSERT INTO `permissions` VALUES (6, 'access-all', NULL, 'customer-reminder-report', NULL, NULL, '2021-10-10 21:56:28', '2021-10-10 21:56:57');
INSERT INTO `permissions` VALUES (7, 'insert', NULL, 'customer-reminder-report', NULL, NULL, '2021-10-10 21:56:33', '2021-10-10 21:57:06');
INSERT INTO `permissions` VALUES (8, 'access', NULL, 'customer', NULL, NULL, '2021-10-10 21:57:34', '2021-10-10 21:57:34');
INSERT INTO `permissions` VALUES (9, 'access-all', NULL, 'customer', NULL, NULL, '2021-10-10 21:57:37', '2021-10-10 21:57:37');
INSERT INTO `permissions` VALUES (10, 'insert', NULL, 'customer', NULL, NULL, '2021-10-10 21:57:41', '2021-10-10 21:57:41');
INSERT INTO `permissions` VALUES (11, 'update', NULL, 'customer', NULL, NULL, '2021-10-10 21:57:44', '2021-10-10 21:57:44');
INSERT INTO `permissions` VALUES (12, 'delete', NULL, 'customer', NULL, NULL, '2021-10-10 21:57:47', '2021-10-10 21:57:47');
INSERT INTO `permissions` VALUES (13, 'import', NULL, 'customer', NULL, NULL, '2021-10-10 21:57:57', '2021-10-10 21:57:57');
INSERT INTO `permissions` VALUES (14, 'assign-sale-pic', NULL, 'customer', NULL, NULL, '2021-10-10 21:58:52', '2021-10-10 22:00:31');
INSERT INTO `permissions` VALUES (15, 'change-sale-pic', NULL, 'customer', NULL, NULL, '2021-10-10 21:59:10', '2021-10-10 22:00:36');
INSERT INTO `permissions` VALUES (16, 'generate-reminder', NULL, 'customer', NULL, NULL, '2021-10-10 21:59:20', '2021-10-10 22:00:05');
INSERT INTO `permissions` VALUES (17, 'reset-reminder', NULL, 'customer', NULL, NULL, '2021-10-10 22:00:00', '2021-10-10 22:00:00');
INSERT INTO `permissions` VALUES (18, 'create-item', NULL, 'customer', NULL, NULL, '2021-10-10 22:01:22', '2021-10-10 22:01:22');
INSERT INTO `permissions` VALUES (19, 'update-item', NULL, 'customer', NULL, NULL, '2021-10-10 22:01:27', '2021-10-10 22:01:27');
INSERT INTO `permissions` VALUES (20, 'delete-item', NULL, 'customer', NULL, NULL, '2021-10-10 22:01:32', '2021-10-10 22:01:32');
INSERT INTO `permissions` VALUES (21, 'change-reminder', NULL, 'customer', NULL, NULL, '2021-10-10 22:02:24', '2021-10-10 22:02:24');
INSERT INTO `permissions` VALUES (22, 'delete-reminder', NULL, 'customer', NULL, NULL, '2021-10-10 22:02:36', '2021-10-10 22:02:36');
INSERT INTO `permissions` VALUES (23, 'upload-attachment', NULL, 'customer', NULL, NULL, '2021-10-10 22:03:15', '2021-10-10 22:03:39');
INSERT INTO `permissions` VALUES (24, 'download-attachment', NULL, 'customer', NULL, NULL, '2021-10-10 22:03:22', '2021-10-10 22:04:19');
INSERT INTO `permissions` VALUES (25, 'remove-attachment', NULL, 'customer', NULL, NULL, '2021-10-10 22:04:13', '2021-10-10 22:04:13');
INSERT INTO `permissions` VALUES (26, 'force-update', NULL, 'customer', NULL, NULL, '2021-10-10 22:05:33', '2021-10-10 22:05:33');
INSERT INTO `permissions` VALUES (27, 'access', NULL, 'customer-contact', NULL, NULL, '2021-10-10 22:06:31', '2021-10-10 22:06:31');
INSERT INTO `permissions` VALUES (28, 'access-all', NULL, 'customer-contact', NULL, NULL, '2021-10-10 22:06:34', '2021-10-10 22:06:34');
INSERT INTO `permissions` VALUES (29, 'insert', NULL, 'customer-contact', NULL, NULL, '2021-10-10 22:06:38', '2021-10-10 22:06:38');
INSERT INTO `permissions` VALUES (30, 'update', NULL, 'customer-contact', NULL, NULL, '2021-10-10 22:06:41', '2021-10-10 22:06:41');
INSERT INTO `permissions` VALUES (31, 'delete', NULL, 'customer-contact', NULL, NULL, '2021-10-10 22:06:43', '2021-10-10 22:06:43');
INSERT INTO `permissions` VALUES (32, 'assign-sale-pic', NULL, 'customer-contact', NULL, NULL, '2021-10-10 22:07:07', '2021-10-10 22:07:07');
INSERT INTO `permissions` VALUES (33, 'access', NULL, 'customer-report', NULL, NULL, '2021-10-10 22:09:24', '2021-10-10 22:09:24');
INSERT INTO `permissions` VALUES (34, 'access-all', NULL, 'customer-report', NULL, NULL, '2021-10-10 22:09:28', '2021-10-10 22:09:28');
INSERT INTO `permissions` VALUES (35, 'access', NULL, 'product', NULL, NULL, '2021-10-10 22:10:09', '2021-10-10 22:10:09');
INSERT INTO `permissions` VALUES (36, 'insert', NULL, 'product', NULL, NULL, '2021-10-10 22:10:12', '2021-10-10 22:10:12');
INSERT INTO `permissions` VALUES (37, 'update', NULL, 'product', NULL, NULL, '2021-10-10 22:10:15', '2021-10-10 22:10:15');
INSERT INTO `permissions` VALUES (38, 'delete', NULL, 'product', NULL, NULL, '2021-10-10 22:10:19', '2021-10-10 22:10:19');
INSERT INTO `permissions` VALUES (39, 'access', NULL, 'reminder-template', NULL, NULL, '2021-10-10 22:11:02', '2021-10-10 22:11:02');
INSERT INTO `permissions` VALUES (40, 'insert', NULL, 'reminder-template', NULL, NULL, '2021-10-10 22:11:05', '2021-10-10 22:11:05');
INSERT INTO `permissions` VALUES (41, 'update', NULL, 'reminder-template', NULL, NULL, '2021-10-10 22:11:08', '2021-10-10 22:11:08');
INSERT INTO `permissions` VALUES (42, 'delete', NULL, 'reminder-template', NULL, NULL, '2021-10-10 22:11:12', '2021-10-10 22:11:12');
INSERT INTO `permissions` VALUES (43, 'create-item', NULL, 'reminder-template', NULL, NULL, '2021-10-10 22:11:43', '2021-10-10 22:12:08');
INSERT INTO `permissions` VALUES (44, 'delete-item', NULL, 'reminder-template', NULL, NULL, '2021-10-10 22:11:53', '2021-10-10 22:11:53');
INSERT INTO `permissions` VALUES (45, 'access', NULL, 'attachment', NULL, NULL, '2021-10-10 22:12:22', '2021-10-10 22:12:22');
INSERT INTO `permissions` VALUES (46, 'access-all', NULL, 'attachment', NULL, NULL, '2021-10-10 22:12:28', '2021-10-10 22:12:28');
INSERT INTO `permissions` VALUES (47, 'access', NULL, 'permission', NULL, NULL, '2021-10-10 22:12:45', '2021-10-10 22:12:45');
INSERT INTO `permissions` VALUES (48, 'insert', NULL, 'permission', NULL, NULL, '2021-10-10 22:12:48', '2021-10-10 22:12:48');
INSERT INTO `permissions` VALUES (49, 'update', NULL, 'permission', NULL, NULL, '2021-10-10 22:12:51', '2021-10-10 22:12:51');
INSERT INTO `permissions` VALUES (50, 'delete', NULL, 'permission', NULL, NULL, '2021-10-10 22:12:54', '2021-10-10 22:12:54');
INSERT INTO `permissions` VALUES (51, 'access', NULL, 'system-config', NULL, NULL, '2021-10-10 22:13:08', '2021-10-10 22:13:08');
INSERT INTO `permissions` VALUES (52, 'access', NULL, 'user', NULL, NULL, '2021-10-10 22:13:43', '2021-10-10 22:13:43');
INSERT INTO `permissions` VALUES (53, 'insert', NULL, 'user', NULL, NULL, '2021-10-10 22:13:47', '2021-10-10 22:13:47');
INSERT INTO `permissions` VALUES (54, 'update', NULL, 'user', NULL, NULL, '2021-10-10 22:13:50', '2021-10-10 22:13:50');
INSERT INTO `permissions` VALUES (55, 'delete', NULL, 'user', NULL, NULL, '2021-10-10 22:13:53', '2021-10-10 22:13:53');
INSERT INTO `permissions` VALUES (56, 'access', NULL, 'user-group', NULL, NULL, '2021-10-10 22:14:21', '2021-10-10 22:14:21');
INSERT INTO `permissions` VALUES (57, 'insert', NULL, 'user-group', NULL, NULL, '2021-10-10 22:14:25', '2021-10-10 22:14:25');
INSERT INTO `permissions` VALUES (58, 'update', NULL, 'user-group', NULL, NULL, '2021-10-10 22:14:30', '2021-10-10 22:14:30');
INSERT INTO `permissions` VALUES (59, 'delete', NULL, 'user-group', NULL, NULL, '2021-10-10 22:14:33', '2021-10-10 22:14:33');
COMMIT;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of products
-- ----------------------------
BEGIN;
INSERT INTO `products` VALUES (1, NULL, 'demo', NULL, NULL, 0, '2021-07-29 21:07:30', '2021-07-29 21:07:30');
INSERT INTO `products` VALUES (2, 'PRODUCTION', 'Production', NULL, NULL, 1, '2021-09-26 20:20:21', '2021-09-26 20:20:28');
INSERT INTO `products` VALUES (3, 'MARKETING', 'Marketing', NULL, NULL, 1, '2021-09-26 20:21:04', '2021-09-26 20:21:04');
INSERT INTO `products` VALUES (4, 'TRY-FREE', 'Try Free', NULL, NULL, 1, '2021-09-26 20:21:16', '2021-09-26 20:21:16');
INSERT INTO `products` VALUES (5, 'VOUCHER', 'Voucher', NULL, NULL, 1, '2021-09-26 20:21:25', '2021-09-26 20:21:25');
INSERT INTO `products` VALUES (6, 'SHOP', 'Shop', NULL, NULL, 1, '2021-09-26 20:21:34', '2021-09-26 20:21:34');
INSERT INTO `products` VALUES (7, 'OTHERS', 'Others', NULL, NULL, 1, '2021-09-26 20:21:44', '2021-09-26 20:21:44');
COMMIT;

-- ----------------------------
-- Table structure for reminder_template_items
-- ----------------------------
DROP TABLE IF EXISTS `reminder_template_items`;
CREATE TABLE `reminder_template_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remind_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reminder_template_id` int unsigned NOT NULL DEFAULT '1',
  `approveed` tinyint NOT NULL DEFAULT '0',
  `approved_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `approved_time` datetime DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reminder_template_id` (`reminder_template_id`),
  CONSTRAINT `reminder_template_items_ibfk_1` FOREIGN KEY (`reminder_template_id`) REFERENCES `reminder_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of reminder_template_items
-- ----------------------------
BEGIN;
INSERT INTO `reminder_template_items` VALUES (1, 'demo', NULL, NULL, NULL, 1, 0, NULL, NULL, 0, NULL, '2021-07-29 21:13:21', '2021-07-29 21:13:21');
INSERT INTO `reminder_template_items` VALUES (2, 'Liên hệ khách hàng cập nhật tình hình kinh doanh hiện tại (Khi nào làm việc lại? Kế hoạch mới về Sản Phẩm, Kênh bán hàng, Kênh truyền thông,...). Hỏi thăm và chúc sức khoẻ.', NULL, NULL, '+0', 2, 0, NULL, NULL, 1, NULL, '2021-10-01 23:49:13', '2021-10-01 23:49:13');
INSERT INTO `reminder_template_items` VALUES (3, 'Gửi khách hàng Report của chiến dịch đã chạy, miêu tả những chỉ số ấn tượng. Cập nhật chươg trình mới của ClubPiaf. Mô tả sơ bộ hình thức hợp tác và lợi ích mang về cho khách hàng. Khai thác&tổng hợp thông tin KH theo Checklist. Đưa thông tin về để làm Ideas Proposal.', NULL, NULL, '+1', 2, 0, NULL, NULL, 1, NULL, '2021-10-01 23:53:32', '2021-10-02 00:48:52');
INSERT INTO `reminder_template_items` VALUES (4, 'Gửi Proposal cho khách', NULL, NULL, '+5', 2, 0, NULL, NULL, 1, NULL, '2021-10-01 23:56:10', '2021-10-02 00:26:23');
INSERT INTO `reminder_template_items` VALUES (5, '(Lần 1) Hỏi ý kiến phản hồi của KH về Proposal', NULL, NULL, '+7', 2, 0, NULL, NULL, 1, NULL, '2021-10-01 23:57:05', '2021-10-01 23:57:05');
INSERT INTO `reminder_template_items` VALUES (6, '(Lần 2) Hỏi ý kiến phản hồi của KH về Proposal', NULL, NULL, '+9', 2, 0, NULL, NULL, 1, NULL, '2021-10-01 23:57:25', '2021-10-01 23:57:25');
INSERT INTO `reminder_template_items` VALUES (7, '(Update feedback 1) Cập nhật kết quả chiến dịch đã chạy cho khách hàng khác', NULL, NULL, '+24', 2, 0, NULL, NULL, 1, NULL, '2021-10-02 00:28:33', '2021-10-02 00:28:33');
INSERT INTO `reminder_template_items` VALUES (8, '(Update feedback 2) Cập nhật kết quả chiến dịch đã chạy cho khách hàng khác', NULL, NULL, '+39', 2, 0, NULL, NULL, 1, NULL, '2021-10-02 00:29:19', '2021-10-02 00:29:19');
INSERT INTO `reminder_template_items` VALUES (9, '(Kết nối tiếp tục 1) Cập nhật cho khách hàng Chiến dịch - Chính sách hợp tác mới của ClubPiaf', NULL, NULL, '+69', 2, 0, NULL, NULL, 1, NULL, '2021-10-02 00:33:12', '2021-10-02 00:33:12');
INSERT INTO `reminder_template_items` VALUES (10, '(Kết nối tiếp tục 2) Cập nhật cho khách hàng Chiến dịch - Chính sách hợp tác mới của ClubPiaf', NULL, NULL, '+99', 2, 0, NULL, NULL, 1, NULL, '2021-10-02 00:33:39', '2021-10-02 00:33:39');
INSERT INTO `reminder_template_items` VALUES (11, 'Liên hệ KH nắm tình hình tổng quan (Sản phẩm/ Nhu cầu/ Mức độ quan tâm/ Mức độ phù hợp/ add chat gửi thông tin KH xem tổng quan)', NULL, NULL, '+0', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:38:14', '2021-10-02 00:38:14');
INSERT INTO `reminder_template_items` VALUES (12, '(Lên Checklist) Hỏi ý kiến phản hồi của KH về platform. Phát triển thông tin khách hàng chi tiết để hiểu về business và nhu cầu chính. Đề xuất vài dịch vụ có thể kết nối trực tiếp với nhu cầu và kết quả mang về. Đề xuất MKT làm Proposal+Quotation', NULL, NULL, '+1', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:43:08', '2021-10-02 00:43:08');
INSERT INTO `reminder_template_items` VALUES (13, 'Gửi Proposal cho khách', NULL, NULL, '+5', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:44:58', '2021-10-02 00:44:58');
INSERT INTO `reminder_template_items` VALUES (14, '(Lần 1) Hỏi ý kiến phản hồi của KH về Proposal', NULL, NULL, '+7', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:45:18', '2021-10-02 00:45:18');
INSERT INTO `reminder_template_items` VALUES (15, '(Lần 2) Hỏi ý kiến phản hồi của KH về Proposal', NULL, NULL, '+9', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:45:34', '2021-10-02 00:45:34');
INSERT INTO `reminder_template_items` VALUES (16, '(Update feedback 1) Cập nhật kết quả chiến dịch đã chạy cho khách hàng khác', NULL, NULL, '+24', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:46:01', '2021-10-02 00:46:01');
INSERT INTO `reminder_template_items` VALUES (17, '(Update feedback 2) Cập nhật kết quả chiến dịch đã chạy cho khách hàng khác', NULL, NULL, '+39', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:46:15', '2021-10-02 00:46:15');
INSERT INTO `reminder_template_items` VALUES (18, '(Kết nối tiếp tục 1) Cập nhật cho khách hàng Chiến dịch - Chính sách hợp tác mới của ClubPiaf', NULL, NULL, '+69', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:46:34', '2021-10-02 00:46:34');
INSERT INTO `reminder_template_items` VALUES (19, '(Kết nối tiếp tục 2) Cập nhật cho khách hàng Chiến dịch - Chính sách hợp tác mới của ClubPiaf', NULL, NULL, '+99', 3, 0, NULL, NULL, 1, NULL, '2021-10-02 00:46:45', '2021-10-02 00:46:45');
COMMIT;

-- ----------------------------
-- Table structure for reminder_templates
-- ----------------------------
DROP TABLE IF EXISTS `reminder_templates`;
CREATE TABLE `reminder_templates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `priority` tinyint NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of reminder_templates
-- ----------------------------
BEGIN;
INSERT INTO `reminder_templates` VALUES (1, NULL, 'demo', NULL, NULL, 0, 0, '2021-07-29 21:07:30', '2021-07-29 21:07:30');
INSERT INTO `reminder_templates` VALUES (2, 'A1', 'Mẫu chăm sóc KH cũ', 'Mẫu kết nối lại KH đã sử dụng dịch vụ', NULL, 0, 1, '2021-10-01 23:46:17', '2021-10-01 23:46:17');
INSERT INTO `reminder_templates` VALUES (3, 'B1', 'Mẫu chăm sóc LEAD mới được phân về', 'Mẫu dành cho khách hàng được assign mới từ data outbound', NULL, 1, 1, '2021-10-02 00:35:38', '2021-10-02 17:57:41');
COMMIT;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `user_group_id` int unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `roles_user_id_foreign` (`user_id`) USING BTREE,
  KEY `roles_user_group_id_foreign` (`user_group_id`) USING BTREE,
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `roles_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` VALUES (1, 1, 1, '2021-07-31 14:12:08', '2021-07-31 14:12:08');
INSERT INTO `roles` VALUES (2, 2, 2, '2021-09-27 16:43:09', '2021-09-27 16:43:09');
INSERT INTO `roles` VALUES (3, 3, 2, '2021-09-27 16:43:09', '2021-09-27 16:43:09');
INSERT INTO `roles` VALUES (4, 4, 2, '2021-09-27 16:43:09', '2021-09-27 16:43:09');
INSERT INTO `roles` VALUES (5, 5, 4, '2021-09-27 16:48:24', '2021-09-27 16:48:24');
INSERT INTO `roles` VALUES (6, 6, 5, '2021-09-27 16:49:17', '2021-09-27 16:49:17');
INSERT INTO `roles` VALUES (7, 7, 3, '2021-09-27 16:50:12', '2021-09-27 16:50:12');
INSERT INTO `roles` VALUES (8, 8, 3, '2021-09-27 16:51:12', '2021-09-27 16:51:12');
INSERT INTO `roles` VALUES (9, 9, 3, '2021-09-27 16:52:04', '2021-09-27 16:52:04');
COMMIT;

-- ----------------------------
-- Table structure for systems
-- ----------------------------
DROP TABLE IF EXISTS `systems`;
CREATE TABLE `systems` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `attribs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of systems
-- ----------------------------
BEGIN;
INSERT INTO `systems` VALUES (1, 'demo', NULL, 'demo', '2021-07-31 14:24:16', '2021-07-31 14:24:16');
COMMIT;

-- ----------------------------
-- Table structure for user_group_permissions
-- ----------------------------
DROP TABLE IF EXISTS `user_group_permissions`;
CREATE TABLE `user_group_permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int unsigned NOT NULL,
  `user_group_id` int unsigned NOT NULL,
  `ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `belong_to_system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'MAIN',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_group_permissions_user_group_id_foreign` (`user_group_id`) USING BTREE,
  KEY `user_group_permissions_permission_id_foreign` (`permission_id`) USING BTREE,
  CONSTRAINT `user_group_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_group_permissions_ibfk_2` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user_group_permissions
-- ----------------------------
BEGIN;
INSERT INTO `user_group_permissions` VALUES (1, 1, 1, '', 'MAIN', '2021-07-31 14:24:34', '2021-07-31 14:24:38');
INSERT INTO `user_group_permissions` VALUES (2, 2, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (3, 3, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (4, 4, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (5, 5, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (6, 6, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (7, 7, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (10, 10, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (26, 27, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (32, 33, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (34, 35, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (35, 36, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (36, 37, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (37, 38, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (38, 39, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (39, 40, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (40, 41, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (41, 42, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (42, 43, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (43, 44, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (44, 45, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (45, 46, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (46, 47, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (47, 48, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (48, 49, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (49, 50, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (50, 51, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (51, 52, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (52, 53, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (53, 54, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (54, 55, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (55, 56, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (56, 57, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (57, 58, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (58, 59, 2, NULL, 'MAIN', '2021-10-10 23:16:59', '2021-10-10 23:16:59');
INSERT INTO `user_group_permissions` VALUES (59, 2, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (60, 5, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (61, 7, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (62, 8, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (63, 11, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (64, 18, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (65, 19, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (66, 20, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (67, 23, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (68, 24, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (69, 25, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (70, 27, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (71, 29, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (72, 30, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (73, 33, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (74, 45, 3, NULL, 'MAIN', '2021-10-10 23:18:21', '2021-10-10 23:18:21');
INSERT INTO `user_group_permissions` VALUES (75, 2, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (76, 3, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (77, 5, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (78, 6, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (79, 8, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (80, 9, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (81, 27, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (82, 28, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (83, 33, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (84, 34, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (85, 35, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (86, 39, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (87, 45, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (88, 46, 4, NULL, 'MAIN', '2021-10-10 23:19:36', '2021-10-10 23:19:36');
INSERT INTO `user_group_permissions` VALUES (89, 2, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (90, 3, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (91, 5, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (92, 6, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (93, 8, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (94, 9, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (95, 27, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (96, 28, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (97, 33, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (98, 34, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (99, 35, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (100, 39, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (101, 45, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (102, 46, 5, NULL, 'MAIN', '2021-10-10 23:19:54', '2021-10-10 23:19:54');
INSERT INTO `user_group_permissions` VALUES (103, 13, 2, NULL, 'MAIN', '2021-10-10 23:30:32', '2021-10-10 23:30:32');
INSERT INTO `user_group_permissions` VALUES (104, 11, 2, NULL, 'MAIN', '2021-10-10 23:39:38', '2021-10-10 23:39:38');
INSERT INTO `user_group_permissions` VALUES (105, 14, 2, NULL, 'MAIN', '2021-10-10 23:39:38', '2021-10-10 23:39:38');
INSERT INTO `user_group_permissions` VALUES (106, 15, 2, NULL, 'MAIN', '2021-10-10 23:40:26', '2021-10-10 23:40:26');
INSERT INTO `user_group_permissions` VALUES (107, 16, 2, NULL, 'MAIN', '2021-10-10 23:40:58', '2021-10-10 23:40:58');
INSERT INTO `user_group_permissions` VALUES (108, 17, 2, NULL, 'MAIN', '2021-10-10 23:40:58', '2021-10-10 23:40:58');
INSERT INTO `user_group_permissions` VALUES (109, 18, 2, NULL, 'MAIN', '2021-10-10 23:47:31', '2021-10-10 23:47:31');
INSERT INTO `user_group_permissions` VALUES (110, 20, 2, NULL, 'MAIN', '2021-10-10 23:47:31', '2021-10-10 23:47:31');
INSERT INTO `user_group_permissions` VALUES (111, 19, 2, NULL, 'MAIN', '2021-10-10 23:47:53', '2021-10-10 23:47:53');
INSERT INTO `user_group_permissions` VALUES (112, 21, 2, NULL, 'MAIN', '2021-10-10 23:53:22', '2021-10-10 23:53:22');
INSERT INTO `user_group_permissions` VALUES (113, 22, 2, NULL, 'MAIN', '2021-10-10 23:53:22', '2021-10-10 23:53:22');
INSERT INTO `user_group_permissions` VALUES (114, 23, 2, NULL, 'MAIN', '2021-10-10 23:53:22', '2021-10-10 23:53:22');
INSERT INTO `user_group_permissions` VALUES (115, 24, 2, NULL, 'MAIN', '2021-10-10 23:53:22', '2021-10-10 23:53:22');
INSERT INTO `user_group_permissions` VALUES (116, 25, 2, NULL, 'MAIN', '2021-10-10 23:53:22', '2021-10-10 23:53:22');
INSERT INTO `user_group_permissions` VALUES (118, 8, 2, NULL, 'MAIN', '2021-10-10 23:55:54', '2021-10-10 23:55:54');
INSERT INTO `user_group_permissions` VALUES (119, 9, 2, NULL, 'MAIN', '2021-10-10 23:58:58', '2021-10-10 23:58:58');
INSERT INTO `user_group_permissions` VALUES (120, 28, 2, NULL, 'MAIN', '2021-10-11 00:00:50', '2021-10-11 00:00:50');
INSERT INTO `user_group_permissions` VALUES (121, 29, 2, NULL, 'MAIN', '2021-10-11 00:02:36', '2021-10-11 00:02:36');
INSERT INTO `user_group_permissions` VALUES (124, 30, 2, NULL, 'MAIN', '2021-10-11 00:04:23', '2021-10-11 00:04:23');
INSERT INTO `user_group_permissions` VALUES (125, 31, 2, NULL, 'MAIN', '2021-10-11 00:04:23', '2021-10-11 00:04:23');
INSERT INTO `user_group_permissions` VALUES (126, 32, 2, NULL, 'MAIN', '2021-10-11 00:05:11', '2021-10-11 00:05:11');
INSERT INTO `user_group_permissions` VALUES (127, 34, 2, NULL, 'MAIN', '2021-10-11 00:06:32', '2021-10-11 00:06:32');
INSERT INTO `user_group_permissions` VALUES (128, 12, 2, NULL, 'MAIN', '2021-10-11 22:37:02', '2021-10-11 22:37:02');

COMMIT;

-- ----------------------------
-- Table structure for user_groups
-- ----------------------------
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_group` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user_groups
-- ----------------------------
BEGIN;
INSERT INTO `user_groups` VALUES (1, 'demo', '', '', '2017-01-06 11:30:33', '2021-07-31 14:09:16');
INSERT INTO `user_groups` VALUES (2, 'Quản trị viên', '', 'Quản trị viên cao nhất', '2017-01-06 11:30:33', '2021-09-27 16:46:19');
INSERT INTO `user_groups` VALUES (3, 'Nhân viên kinh doanh', '', 'Nhóm nhân viên thuộc phòng Sale trực tiếp liên hệ với khách hàng và tương tác với đơn hàng.', '2021-07-31 14:08:51', '2021-07-31 14:09:10');
INSERT INTO `user_groups` VALUES (4, 'Admin giám sát', '', 'Nhóm nhân viên thuộc nhóm Manager trực tiếp giám sát nhân viên kinh doanh tương tác với khách hàng và với đơn hàng.', '2021-09-27 16:43:34', '2021-09-27 16:46:05');
INSERT INTO `user_groups` VALUES (5, 'Nhân viên giám sát', '', 'Nhóm nhân viên thuộc phòng Sale giám sát nhân viên kinh doanh tương tác với khách hàng và với đơn hàng.', '2021-09-27 16:46:59', '2021-09-27 16:47:07');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token_login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `device_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login_backend` tinyint DEFAULT '0',
  `login_frontend` tinyint DEFAULT '0',
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthday` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `locked` tinyint DEFAULT '0',
  `guest` tinyint DEFAULT '0',
  `attribs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `protected` tinyint DEFAULT '0',
  `anonymous` tinyint DEFAULT '0',
  `user_group_id` int DEFAULT '1',
  `user_group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'Demo', '0909000000', '$2y$10$o8GXKWAYfjxZJuy0bVY7NOzwGCsMVttPS6Vh7ufnCqlgja/9yww7a', '', '', '', '', 0, 0, '0', '2019-04-08 17:27:14', '0909000000', 'demo_user@gmail.com', '', 0, 1, 0, '', '2020-02-25 16:00:46', 0, 0, 1, NULL, '', '2017-03-02 11:00:00', '2021-07-31 14:58:23');
INSERT INTO `users` VALUES (2, 'Super Administrator', 'root', '$2y$10$whV/Ze0HSvjLJQyeT.R58.w4caaneFPRzhDVDweJ56fZQWYm1IaPm', 'upload/admin//vit-vang-boi-roi-14-800x800.jpeg', 'YzeZFlVGRlK7f5OKlbsUzXwng5xDP3SiFutdA0V69l3IAO3FzokmgczGZlu3', 'gcAQe5fSd43JKuCCz1DhDwNk7LM452sQ7Y163caX', 'rtN6425OFdTqkF9wDez6JTWqozhGx5JrIJ13Sc2QZsPH6ZhNQi', 1, 1, '1', '1995-08-27 00:00:00', '0989696969', 'superadmin@gmail.com', NULL, 1, 0, 0, NULL, '2021-10-10 16:54:57', 1, 1, 2, NULL, NULL, '2017-03-02 11:00:00', '2021-10-10 16:54:57');
INSERT INTO `users` VALUES (3, 'Administrator', 'admin', '$2y$10$joQWzd/0odZHJ1Ub6O2TSOvqz6BxocynNww62jCgY4Z.ij9vVI87.', NULL, 'keeFPZ9zoTpxAI0iEUgts0jnJq8WhsOOI5kPyk3LcvWZcQJ8hHSJpoWti8k5', 'gcAQe5fSd43JKuCCz1DhDwNk7LM452sQ7Y163caX', '4dwNY2nepQ2bZpUB1rvqtHX7uBuxIKSfE19dCHArikkfp3Pe6I', 1, 1, '1', '2019-04-08 00:00:00', 'admin', 'admin@gmail.com', NULL, 1, 0, 0, '', '2021-08-23 17:41:48', 1, 0, 1, NULL, '', '2017-03-02 11:00:00', '2021-08-23 17:41:48');
INSERT INTO `users` VALUES (4, 'Đinh Thị Kim Trinh', '0973544453', '$2y$10$wjNDW1V5bPzAsZlc.80s5OeowAeo4LeCp4Th0D6FHJBtsND5EmFyG', NULL, NULL, NULL, 'cA8fYhFQYvOkJBPJaGiqbb2DQ0D1qZQRVTxIjg75FlbdrPTScn', 1, 1, '0', NULL, '0973544453', 'dinhtrinh@dmnc.vn', NULL, 1, 0, 0, NULL, '2021-09-27 16:45:03', 0, 0, 2, NULL, NULL, '2021-09-27 16:43:09', '2021-09-27 16:45:03');
INSERT INTO `users` VALUES (5, 'Lê Thị Tuyết Ngân', '0973456737', '$2y$10$Q3Xdb2ubnRFMbIgoj3RVeOApCmp0bdHksXh7oxQjo2pPmH1CsDPDC', NULL, NULL, NULL, 'B5mqZQiKyIwF6uXq4511VnpngHKcOUgzhj70VHtksqEgQ0RE3n', 1, 1, '0', NULL, '0973456737', 'lengan@dmnc.vn', NULL, 1, 0, 0, NULL, '2021-09-27 16:48:34', 0, 0, 4, NULL, NULL, '2021-09-27 16:48:24', '2021-09-27 16:48:34');
INSERT INTO `users` VALUES (6, 'Phan Hải Yến', '0967827702', '$2y$10$TLjZUcpr3vuyxg1BylKXper5CzXfIj3CgIwi9WLaebpsiWoBSCU9C', NULL, NULL, NULL, NULL, 1, 1, '0', '1970-01-01 08:00:00', '0967827702', 'phanyen@dmnc.vn', NULL, 1, 0, 0, NULL, '2021-09-27 16:49:17', 0, 0, 5, 'Nhân viên giám sát', NULL, '2021-09-27 16:49:17', '2021-09-27 16:50:17');
INSERT INTO `users` VALUES (7, 'Nguyễn Thị Hồng Thắm', '0931326467', '$2y$10$5lCmlxc8GZ/Xp5lveVw5yuBeFSnzB/9MC0zVI3cwYveSrBvWfDVQq', NULL, NULL, NULL, NULL, 1, 1, '0', NULL, '0931326467', 'nguyentham@dmnc.vn', NULL, 1, 0, 0, NULL, '2021-09-27 16:50:12', 0, 0, 3, NULL, NULL, '2021-09-27 16:50:12', '2021-09-27 16:50:12');
INSERT INTO `users` VALUES (8, 'Đinh Lệ Thanh', '0931459095', '$2y$10$94Zf1vpEMJmW6Ok/5zRjHO7IZb7eHBpZxRCX643onfKYARC9T1Gvq', NULL, NULL, NULL, NULL, 1, 1, '0', NULL, '0931459095', 'dinhthanh@dmnc.vn', NULL, 1, 0, 0, NULL, '2021-09-27 16:51:12', 0, 0, 3, NULL, NULL, '2021-09-27 16:51:12', '2021-09-27 16:51:12');
INSERT INTO `users` VALUES (9, 'Nguyễn Thị Kim Châu', '0789901011', '$2y$10$tMsm4D4s4VhnQPmmoTojvu1OTUfGh0QPlMWXVcPH2n6nWRLC6RGam', NULL, NULL, NULL, NULL, 1, 1, '0', '1970-01-01 08:00:00', '0789901011', 'nguyenchau@dmnc.vn', NULL, 1, 0, 0, NULL, '2021-09-27 16:52:04', 0, 0, 3, 'Nhân viên kinh doanh', NULL, '2021-09-27 16:52:04', '2021-09-27 17:12:33');
COMMIT;

-- ----------------------------
-- View structure for v_customer_contacts
-- ----------------------------
DROP VIEW IF EXISTS `v_customer_contacts`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_customer_contacts` AS select `customer_contacts`.`id` AS `id`,`customer_contacts`.`name` AS `name`,`customer_contacts`.`phone` AS `phone`,`customer_contacts`.`email` AS `email`,`customer_contacts`.`client_name` AS `client_name`,`customer_contacts`.`field` AS `field`,`customer_contacts`.`demand` AS `demand`,`customer_contacts`.`concern` AS `concern`,`customer_contacts`.`note` AS `note`,`customer_contacts`.`customer_id` AS `customer_id`,`customer_contacts`.`user_created_id` AS `user_created_id`,`customer_contacts`.`user_sale_id` AS `user_sale_id`,`customer_contacts`.`active` AS `active`,`customer_contacts`.`assigned_at` AS `assigned_at`,`customer_contacts`.`created_at` AS `created_at`,`customer_contacts`.`updated_at` AS `updated_at`,`customers`.`code` AS `c_code`,`customers`.`name` AS `c_name`,`customers`.`field` AS `c_field`,`customers`.`branch` AS `c_branch`,`customers`.`state` AS `c_state`,`customers`.`active` AS `c_active`,`customers`.`contract_state` AS `c_contract_state`,`customers`.`payment_state` AS `c_payment_state`,`customers`.`state_changed_at` AS `c_state_changed_at`,`customers`.`phone` AS `c_phone`,`customers`.`email` AS `c_email`,`customers`.`company_name` AS `c_company_name`,`customers`.`brand_name` AS `c_brand_name`,`users`.`fullname` AS `cu_fullname`,`users`.`avatar` AS `cu_avatar`,`users`.`phone` AS `cu_phone`,`users`.`email` AS `cu_email` from ((`customer_contacts` join `customers` on((`customer_contacts`.`customer_id` = `customers`.`id`))) join `users` on((`customers`.`user_assigned_id` = `users`.`id`)));

-- ----------------------------
-- View structure for v_customer_messages
-- ----------------------------
DROP VIEW IF EXISTS `v_customer_messages`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_customer_messages` AS select `customer_messages`.`id` AS `id`,`customer_messages`.`type` AS `type`,`customer_messages`.`title` AS `title`,`customer_messages`.`content` AS `content`,`customer_messages`.`note` AS `note`,`customer_messages`.`customer_id` AS `customer_id`,`customer_messages`.`user_created_id` AS `user_created_id`,`customer_messages`.`created_at` AS `created_at`,`customer_messages`.`updated_at` AS `updated_at`,`users`.`fullname` AS `fullname`,`users`.`avatar` AS `avatar`,`users`.`phone` AS `phone`,`users`.`email` AS `email` from (`customer_messages` join `users` on((`customer_messages`.`user_created_id` = `users`.`id`)));

-- ----------------------------
-- View structure for v_customer_reminders
-- ----------------------------
DROP VIEW IF EXISTS `v_customer_reminders`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_customer_reminders` AS select `customer_reminders`.`id` AS `id`,`customer_reminders`.`name` AS `name`,`customer_reminders`.`type` AS `type`,`customer_reminders`.`remind_at` AS `remind_at`,`customer_reminders`.`customer_id` AS `customer_id`,`customer_reminders`.`reminder_user_id` AS `reminder_user_id`,`customer_reminders`.`user_created_id` AS `user_created_id`,`customer_reminders`.`approveed` AS `approveed`,`customer_reminders`.`approved_note` AS `approved_note`,`customer_reminders`.`approved_time` AS `approved_time`,`customer_reminders`.`priority` AS `priority`,`customer_reminders`.`active` AS `active`,`customer_reminders`.`note` AS `note`,`customer_reminders`.`created_at` AS `created_at`,`customer_reminders`.`updated_at` AS `updated_at`,`uc`.`fullname` AS `uc_fullname`,`uc`.`avatar` AS `uc_avatar`,`ru`.`fullname` AS `ru_fullname`,`ru`.`avatar` AS `ru_avatar`,`ru`.`phone` AS `ru_phone`,`ru`.`email` AS `ru_email`,`ru`.`gender` AS `ru_gender`,`customers`.`name` AS `customer_name`,`customers`.`display_name` AS `display_name`,`customers`.`phone` AS `phone`,`customers`.`email` AS `email`,`customers`.`company_name` AS `company_name`,`customers`.`brand_name` AS `brand_name`,`customers`.`company_address` AS `company_address`,`customers`.`link` AS `link`,`customers`.`field` AS `field`,`customers`.`branch` AS `branch`,`customers`.`state` AS `state`,`customers`.`contract_state` AS `contract_state`,`customers`.`payment_state` AS `payment_state`,`customers`.`sign_date` AS `sign_date`,`customers`.`describe` AS `describe`,`customers`.`source` AS `source`,`customers`.`demand` AS `demand`,`customers`.`concern` AS `concern`,`customers`.`code` AS `code` from (((`customer_reminders` join `users` `uc` on((`customer_reminders`.`user_created_id` = `uc`.`id`))) join `users` `ru` on((`customer_reminders`.`reminder_user_id` = `ru`.`id`))) join `customers` on((`customer_reminders`.`customer_id` = `customers`.`id`)));

SET FOREIGN_KEY_CHECKS = 1;
