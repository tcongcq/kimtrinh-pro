/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80026
 Source Host           : localhost:3306
 Source Schema         : kimtrinh_re_helper

 Target Server Type    : MySQL
 Target Server Version : 80026
 File Encoding         : 65001

 Date: 31/08/2021 17:00:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for attachments
-- ----------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `src` text NOT NULL,
  `thumbnail_src` text,
  `file_type` varchar(255) DEFAULT NULL,
  `file_extension` varchar(30) DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) DEFAULT NULL,
  `important` tinyint NOT NULL DEFAULT '0',
  `note` text,
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
-- Table structure for customer_messages
-- ----------------------------
DROP TABLE IF EXISTS `customer_messages`;
CREATE TABLE `customer_messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `title` text,
  `content` text NOT NULL,
  `note` text,
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
  `name` varchar(255) NOT NULL,
  `emotion` varchar(255) DEFAULT NULL,
  `description` text,
  `customer_id` int unsigned NOT NULL DEFAULT '1' COMMENT ' ',
  `user_created_id` int NOT NULL DEFAULT '1',
  `note` text,
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
-- Table structure for customer_reminders
-- ----------------------------
DROP TABLE IF EXISTS `customer_reminders`;
CREATE TABLE `customer_reminders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `remind_at` datetime DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `reminder_user_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `approveed` tinyint NOT NULL DEFAULT '0',
  `approved_note` text,
  `approved_time` datetime DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `note` text,
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
INSERT INTO `customer_reminders` VALUES (1, 'demo', NULL, NULL, 1, 1, 1, 0, NULL, NULL, 0, NULL, '2021-07-29 21:13:21', '2021-07-29 21:13:21');
COMMIT;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `classify` varchar(50) DEFAULT 'DRAFT',
  `state` varchar(255) DEFAULT 'DRAFT',
  `active` tinyint DEFAULT '1',
  `zalo` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `other_social` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ thường trú',
  `current_address` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ hiện tại',
  `id_card_number` varchar(20) DEFAULT NULL,
  `id_card_type` varchar(30) DEFAULT NULL,
  `id_card_date` varchar(20) DEFAULT NULL,
  `id_card_agency_issued` varchar(30) DEFAULT NULL COMMENT 'Nơi cấp cmnd',
  `demand` text COMMENT 'Nhu cầu',
  `concern` text COMMENT 'Lo lắng',
  `product_category_id` int unsigned NOT NULL DEFAULT '1' COMMENT 'Sản phẩm',
  `product_code` varchar(20) DEFAULT NULL COMMENT 'Mã sản phẩm',
  `product_type` varchar(100) DEFAULT '0' COMMENT 'Loại hình sản phẩm',
  `land_area` varchar(255) DEFAULT '0' COMMENT 'Diện tích đất',
  `floor_area_used` varchar(255) DEFAULT NULL COMMENT 'Diện tích sàn sử dụng',
  `standard_handover` text COMMENT 'Tiêu chuần bàn giao',
  `product_price` bigint DEFAULT '0' COMMENT 'Giá trị sản phẩm sau ưu đãi',
  `guarantees` bigint DEFAULT NULL COMMENT 'Khoản đảm bảo',
  `sale_name` varchar(255) DEFAULT NULL,
  `sale_phone` varchar(255) DEFAULT NULL,
  `sale_email` varchar(255) DEFAULT NULL,
  `user_assigned_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `first_payment` bigint DEFAULT NULL COMMENT 'Tiền đợt 1 đóng (tiền giữ chổ)',
  `second_payment` bigint DEFAULT NULL COMMENT 'Tiền đợt 3 đóng (trong vòng 12 ngày)',
  `listed_price` bigint DEFAULT NULL COMMENT 'Giá niêm yết',
  `final_price` bigint DEFAULT NULL COMMENT 'Giá sau cùng',
  `payment_schedule` text COMMENT 'Lịch thanh toán',
  `upload_dir` varchar(255) DEFAULT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `careful` text,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`) USING BTREE,
  KEY `user_assigned_id` (`user_assigned_id`) USING BTREE,
  KEY `product_category_id` (`product_category_id`),
  CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`),
  CONSTRAINT `customers_ibfk_3` FOREIGN KEY (`user_assigned_id`) REFERENCES `users` (`id`),
  CONSTRAINT `customers_ibfk_4` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of customers
-- ----------------------------
BEGIN;
INSERT INTO `customers` VALUES (1, NULL, NULL, NULL, 'demo', NULL, 'DRAFT', 'DRAFT', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '0', '0', NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-07-29 21:07:34', '2021-07-29 21:10:19', '2021-07-29 21:07:34', NULL);
COMMIT;

-- ----------------------------
-- Table structure for helper_reminders
-- ----------------------------
DROP TABLE IF EXISTS `helper_reminders`;
CREATE TABLE `helper_reminders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `remind_at` datetime DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `reminder_user_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `approveed` tinyint NOT NULL DEFAULT '0',
  `approved_note` text,
  `approved_time` datetime DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reminder_user_id` (`reminder_user_id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`) USING BTREE,
  KEY `customer_id` (`customer_id`) USING BTREE,
  CONSTRAINT `helper_reminders_ibfk_1` FOREIGN KEY (`reminder_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `helper_reminders_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`),
  CONSTRAINT `helper_reminders_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of helper_reminders
-- ----------------------------
BEGIN;
INSERT INTO `helper_reminders` VALUES (1, 'demo', NULL, NULL, 1, 1, 1, 0, NULL, NULL, 0, NULL, '2021-07-29 21:13:21', '2021-07-29 21:13:21');
COMMIT;

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
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
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
  `name` varchar(255) NOT NULL,
  `table` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` VALUES (1, 'demo', NULL, 'demo', NULL, NULL, '2021-07-31 14:11:41', '2021-07-31 14:11:41');
INSERT INTO `permissions` VALUES (2, 'access', NULL, 'user', NULL, NULL, '2021-08-10 20:20:27', '2021-08-10 21:45:07');
INSERT INTO `permissions` VALUES (3, 'insert', NULL, 'user', NULL, NULL, '2021-08-10 20:20:43', '2021-08-10 21:45:49');
INSERT INTO `permissions` VALUES (4, 'update', NULL, 'user', NULL, NULL, '2021-08-10 20:20:55', '2021-08-10 21:46:26');
INSERT INTO `permissions` VALUES (5, 'delete', NULL, 'user', NULL, NULL, '2021-08-10 20:21:00', '2021-08-10 21:46:29');
INSERT INTO `permissions` VALUES (6, 'access-all', NULL, 'user', NULL, NULL, '2021-08-10 20:21:12', '2021-08-10 21:45:44');
INSERT INTO `permissions` VALUES (7, 'access', NULL, 'user-group', NULL, NULL, '2021-08-10 20:22:25', '2021-08-10 21:45:12');
INSERT INTO `permissions` VALUES (8, 'insert', NULL, 'user-group', NULL, NULL, '2021-08-10 20:22:34', '2021-08-10 21:45:53');
INSERT INTO `permissions` VALUES (9, 'update', NULL, 'user-group', NULL, NULL, '2021-08-10 20:22:39', '2021-08-10 21:46:25');
INSERT INTO `permissions` VALUES (10, 'delete', NULL, 'user-group', NULL, NULL, '2021-08-10 20:22:42', '2021-08-10 21:46:31');
INSERT INTO `permissions` VALUES (11, 'access', NULL, 'system-config', NULL, NULL, '2021-08-10 20:23:07', '2021-08-10 21:45:17');
INSERT INTO `permissions` VALUES (12, 'access', NULL, 'permission', NULL, NULL, '2021-08-10 20:23:31', '2021-08-10 21:45:17');
INSERT INTO `permissions` VALUES (13, 'access', NULL, 'attachment', NULL, NULL, '2021-08-10 20:23:45', '2021-08-10 21:45:14');
INSERT INTO `permissions` VALUES (14, 'access-all', NULL, 'attachment', NULL, NULL, '2021-08-10 20:24:03', '2021-08-10 21:45:41');
INSERT INTO `permissions` VALUES (15, 'access', NULL, 'user-storage-box', NULL, NULL, '2021-08-10 20:24:13', '2021-08-10 21:45:16');
INSERT INTO `permissions` VALUES (16, 'insert', NULL, 'user-storage-box', NULL, NULL, '2021-08-10 20:24:17', '2021-08-10 21:45:55');
INSERT INTO `permissions` VALUES (17, 'update', NULL, 'user-storage-box', NULL, NULL, '2021-08-10 20:24:21', '2021-08-10 21:46:24');
INSERT INTO `permissions` VALUES (18, 'delete', NULL, 'user-storage-box', NULL, NULL, '2021-08-10 20:24:26', '2021-08-10 21:46:32');
INSERT INTO `permissions` VALUES (19, 'access', NULL, 'product-category', NULL, NULL, '2021-08-10 20:24:47', '2021-08-10 21:45:20');
INSERT INTO `permissions` VALUES (20, 'access-all', NULL, 'product-category', NULL, NULL, '2021-08-10 20:24:51', '2021-08-10 21:45:40');
INSERT INTO `permissions` VALUES (21, 'insert', NULL, 'product-category', NULL, NULL, '2021-08-10 20:24:55', '2021-08-10 21:45:56');
INSERT INTO `permissions` VALUES (22, 'update', NULL, 'product-category', NULL, NULL, '2021-08-10 20:25:00', '2021-08-10 21:46:23');
INSERT INTO `permissions` VALUES (23, 'delete', NULL, 'product-category', NULL, NULL, '2021-08-10 20:25:03', '2021-08-10 21:46:32');
INSERT INTO `permissions` VALUES (24, 'access', NULL, 'customer-plan', NULL, NULL, '2021-08-10 20:25:23', '2021-08-10 21:45:21');
INSERT INTO `permissions` VALUES (25, 'access-all', NULL, 'customer-plan', NULL, NULL, '2021-08-10 20:25:30', '2021-08-10 21:45:39');
INSERT INTO `permissions` VALUES (26, 'insert', NULL, 'customer-plan', NULL, NULL, '2021-08-10 20:25:33', '2021-08-10 21:45:59');
INSERT INTO `permissions` VALUES (27, 'update', NULL, 'customer-plan', NULL, NULL, '2021-08-10 20:25:42', '2021-08-10 21:46:23');
INSERT INTO `permissions` VALUES (28, 'delete', NULL, 'customer-plan', NULL, NULL, '2021-08-10 20:25:49', '2021-08-10 21:46:33');
INSERT INTO `permissions` VALUES (29, 'access', NULL, 'customer-report', NULL, NULL, '2021-08-10 20:25:58', '2021-08-10 21:45:23');
INSERT INTO `permissions` VALUES (30, 'access', NULL, 'customer', NULL, NULL, '2021-08-10 20:26:05', '2021-08-10 21:45:23');
INSERT INTO `permissions` VALUES (31, 'access-all', NULL, 'customer', NULL, NULL, '2021-08-10 20:26:11', '2021-08-10 21:45:37');
INSERT INTO `permissions` VALUES (32, 'insert', NULL, 'customer', NULL, NULL, '2021-08-10 20:26:30', '2021-08-10 21:46:00');
INSERT INTO `permissions` VALUES (33, 'update', NULL, 'customer', NULL, NULL, '2021-08-10 20:26:34', '2021-08-10 21:46:05');
INSERT INTO `permissions` VALUES (34, 'delete', NULL, 'customer', NULL, NULL, '2021-08-10 20:26:38', '2021-08-10 21:46:31');
INSERT INTO `permissions` VALUES (35, 'upload-attachment', NULL, 'customer', NULL, NULL, '2021-08-10 20:27:00', '2021-08-10 21:46:53');
INSERT INTO `permissions` VALUES (36, 'delete-attachment', NULL, 'customer', NULL, NULL, '2021-08-10 20:27:15', '2021-08-10 21:46:42');
INSERT INTO `permissions` VALUES (37, 'access', NULL, 'customer-reminder-report', NULL, NULL, '2021-08-10 20:28:04', '2021-08-10 21:45:28');
INSERT INTO `permissions` VALUES (38, 'access-all', NULL, 'customer-reminder-report', NULL, NULL, '2021-08-10 20:28:09', '2021-08-10 21:45:36');
INSERT INTO `permissions` VALUES (39, 'access', NULL, 'customer-reminder', NULL, NULL, '2021-08-10 20:28:38', '2021-08-10 21:45:30');
INSERT INTO `permissions` VALUES (40, 'access-all', NULL, 'customer-reminder', NULL, NULL, '2021-08-10 20:28:47', '2021-08-10 21:45:34');
COMMIT;

-- ----------------------------
-- Table structure for product_categories
-- ----------------------------
DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `note` text,
  `active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of product_categories
-- ----------------------------
BEGIN;
INSERT INTO `product_categories` VALUES (1, 'demo', NULL, NULL, 0, '2021-07-29 21:07:30', '2021-07-29 21:07:30');
INSERT INTO `product_categories` VALUES (2, 'Nhà phố', NULL, NULL, 1, '2021-08-10 13:13:24', '2021-08-10 13:13:43');
INSERT INTO `product_categories` VALUES (3, 'Biệt thự song lập', NULL, NULL, 1, '2021-08-10 13:13:24', '2021-08-10 13:13:43');
INSERT INTO `product_categories` VALUES (4, 'Biệt thự đơn lập', NULL, NULL, 1, '2021-08-10 13:13:24', '2021-08-10 13:13:43');
INSERT INTO `product_categories` VALUES (5, 'Shop house', NULL, NULL, 1, '2021-08-10 13:13:24', '2021-08-10 13:13:43');
INSERT INTO `product_categories` VALUES (6, 'Căn hộ chung cư', NULL, NULL, 0, '2021-08-14 12:03:57', '2021-08-14 12:03:57');
INSERT INTO `product_categories` VALUES (7, 'Bất động sản nghỉ dưỡng', NULL, NULL, 0, '2021-08-14 12:04:17', '2021-08-14 12:04:17');
INSERT INTO `product_categories` VALUES (8, 'Officetel', NULL, NULL, 0, '2021-08-14 12:04:29', '2021-08-14 12:04:29');
INSERT INTO `product_categories` VALUES (9, 'Phòng trọ cho thuê', NULL, NULL, 0, '2021-08-14 12:04:38', '2021-08-14 12:04:38');
INSERT INTO `product_categories` VALUES (10, 'Đầu tư nhà xưởng', NULL, NULL, 0, '2021-08-14 12:04:49', '2021-08-14 12:04:49');
INSERT INTO `product_categories` VALUES (11, 'Đất nền', NULL, NULL, 0, '2021-08-14 12:05:11', '2021-08-14 12:05:11');
INSERT INTO `product_categories` VALUES (12, 'Khách chưa xác định', NULL, NULL, 1, '2021-08-14 12:05:19', '2021-08-14 12:06:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` VALUES (1, 1, 1, '2021-07-31 14:12:08', '2021-07-31 14:12:08');
INSERT INTO `roles` VALUES (2, 2, 2, '2021-08-10 20:32:42', '2021-08-10 20:32:42');
INSERT INTO `roles` VALUES (3, 3, 2, '2021-08-10 20:33:20', '2021-08-10 20:33:20');
INSERT INTO `roles` VALUES (4, 4, 3, '2021-08-13 18:15:21', '2021-08-13 18:15:21');
COMMIT;

-- ----------------------------
-- Table structure for systems
-- ----------------------------
DROP TABLE IF EXISTS `systems`;
CREATE TABLE `systems` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `attribs` text,
  `value` text NOT NULL,
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
  `ids` text,
  `belong_to_system` varchar(255) NOT NULL DEFAULT 'MAIN',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_group_permissions_user_group_id_foreign` (`user_group_id`) USING BTREE,
  KEY `user_group_permissions_permission_id_foreign` (`permission_id`) USING BTREE,
  CONSTRAINT `user_group_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_group_permissions_ibfk_2` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user_group_permissions
-- ----------------------------
BEGIN;
INSERT INTO `user_group_permissions` VALUES (1, 1, 1, '', 'MAIN', '2021-07-31 14:24:34', '2021-07-31 14:24:38');
INSERT INTO `user_group_permissions` VALUES (2, 39, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (3, 40, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (4, 37, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (5, 38, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (6, 30, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (7, 31, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (8, 32, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (9, 33, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (10, 34, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (11, 35, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (12, 36, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (19, 19, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (20, 20, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (21, 21, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (22, 22, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (23, 23, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (28, 13, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (30, 12, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (31, 11, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (32, 7, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (33, 8, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (34, 9, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (35, 10, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (36, 2, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (37, 3, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (38, 4, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (39, 5, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (40, 6, 2, NULL, 'MAIN', '2021-08-10 20:34:59', '2021-08-10 20:34:59');
INSERT INTO `user_group_permissions` VALUES (41, 39, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (42, 37, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (43, 30, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (44, 32, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (45, 33, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (46, 34, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (47, 35, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (48, 36, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (56, 13, 3, NULL, 'MAIN', '2021-08-10 23:47:49', '2021-08-10 23:47:49');
INSERT INTO `user_group_permissions` VALUES (58, 14, 2, NULL, 'MAIN', '2021-08-11 19:05:09', '2021-08-11 19:05:09');
INSERT INTO `user_group_permissions` VALUES (59, 15, 3, NULL, 'MAIN', '2021-08-16 22:19:58', '2021-08-16 22:19:58');
INSERT INTO `user_group_permissions` VALUES (60, 16, 3, NULL, 'MAIN', '2021-08-16 22:19:58', '2021-08-16 22:19:58');
INSERT INTO `user_group_permissions` VALUES (61, 17, 3, NULL, 'MAIN', '2021-08-16 22:19:58', '2021-08-16 22:19:58');
INSERT INTO `user_group_permissions` VALUES (62, 18, 3, NULL, 'MAIN', '2021-08-16 22:19:58', '2021-08-16 22:19:58');
INSERT INTO `user_group_permissions` VALUES (63, 15, 2, NULL, 'MAIN', '2021-08-16 22:20:03', '2021-08-16 22:20:03');
INSERT INTO `user_group_permissions` VALUES (64, 16, 2, NULL, 'MAIN', '2021-08-16 22:20:03', '2021-08-16 22:20:03');
INSERT INTO `user_group_permissions` VALUES (65, 17, 2, NULL, 'MAIN', '2021-08-16 22:20:03', '2021-08-16 22:20:03');
INSERT INTO `user_group_permissions` VALUES (66, 18, 2, NULL, 'MAIN', '2021-08-16 22:20:03', '2021-08-16 22:20:03');
COMMIT;

-- ----------------------------
-- Table structure for user_groups
-- ----------------------------
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user_groups
-- ----------------------------
BEGIN;
INSERT INTO `user_groups` VALUES (1, 'demo', '', '2017-01-06 11:30:33', '2021-07-31 14:09:16');
INSERT INTO `user_groups` VALUES (2, 'Manager', 'Quản trị viên cao nhất', '2017-01-06 11:30:33', '2021-07-31 14:09:07');
INSERT INTO `user_groups` VALUES (3, 'Nhân viên kinh doanh', 'Nhóm nhân viên thuộc phòng Sale trực tiếp liên hệ với khách hàng và tương tác với đơn hàng.', '2021-07-31 14:08:51', '2021-07-31 14:09:10');
COMMIT;

-- ----------------------------
-- Table structure for user_storage_boxes
-- ----------------------------
DROP TABLE IF EXISTS `user_storage_boxes`;
CREATE TABLE `user_storage_boxes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `attribs` text,
  `value` text NOT NULL,
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`),
  CONSTRAINT `user_storage_boxes_ibfk_1` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user_storage_boxes
-- ----------------------------
BEGIN;
INSERT INTO `user_storage_boxes` VALUES (1, NULL, NULL, 'demo', 1, '2021-07-29 21:11:34', '2021-07-29 21:11:34');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` text,
  `remember_token` varchar(100) DEFAULT NULL,
  `token_login` varchar(255) DEFAULT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `login_backend` tinyint DEFAULT '0',
  `login_frontend` tinyint DEFAULT '0',
  `gender` varchar(255) DEFAULT NULL,
  `birthday` varchar(20) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `active` tinyint NOT NULL DEFAULT '0',
  `locked` tinyint DEFAULT '0',
  `guest` tinyint DEFAULT '0',
  `attribs` text,
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `protected` tinyint DEFAULT '0',
  `anonymous` tinyint DEFAULT '0',
  `user_group_id` int DEFAULT '1',
  `user_group_name` varchar(255) DEFAULT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'Demo', '0909000000', '$2y$10$o8GXKWAYfjxZJuy0bVY7NOzwGCsMVttPS6Vh7ufnCqlgja/9yww7a', '', '', '', '', 0, 0, '0', '2019-04-08 17:27:14', '0909000000', 'demo_user@gmail.com', '', 0, 1, 0, '', '2020-02-25 16:00:46', 0, 0, 1, NULL, '', '2017-03-02 11:00:00', '2021-07-31 14:58:23');
INSERT INTO `users` VALUES (2, 'Super Administrator', 'root', '$2y$10$whV/Ze0HSvjLJQyeT.R58.w4caaneFPRzhDVDweJ56fZQWYm1IaPm', 'upload/admin//vit-vang-boi-roi-14-800x800.jpeg', 'CHpMQGMYXlvWqPbAzCloA8XCtg98XyJbXjRRpV5l7NlJ1TfqIdGFetkxS6NZ', 'gcAQe5fSd43JKuCCz1DhDwNk7LM452sQ7Y163caX', 'rtN6425OFdTqkF9wDez6JTWqozhGx5JrIJ13Sc2QZsPH6ZhNQi', 1, 1, '1', '1995-08-27 00:00:00', '0989696969', 'superadmin@gmail.com', NULL, 1, 0, 0, NULL, '2021-08-19 00:52:03', 1, 1, 2, NULL, NULL, '2017-03-02 11:00:00', '2021-08-19 00:52:03');
INSERT INTO `users` VALUES (3, 'Administrator', 'admin', '$2y$10$joQWzd/0odZHJ1Ub6O2TSOvqz6BxocynNww62jCgY4Z.ij9vVI87.', NULL, 'keeFPZ9zoTpxAI0iEUgts0jnJq8WhsOOI5kPyk3LcvWZcQJ8hHSJpoWti8k5', 'gcAQe5fSd43JKuCCz1DhDwNk7LM452sQ7Y163caX', '4dwNY2nepQ2bZpUB1rvqtHX7uBuxIKSfE19dCHArikkfp3Pe6I', 1, 1, '1', '2019-04-08 00:00:00', 'admin', 'admin@gmail.com', NULL, 1, 0, 0, '', '2021-08-23 17:41:48', 1, 0, 1, NULL, '', '2017-03-02 11:00:00', '2021-08-23 17:41:48');
COMMIT;

-- ----------------------------
-- View structure for v_customer_messages
-- ----------------------------
DROP VIEW IF EXISTS `v_customer_messages`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_customer_messages` AS select `customer_messages`.`id` AS `id`,`customer_messages`.`type` AS `type`,`customer_messages`.`title` AS `title`,`customer_messages`.`content` AS `content`,`customer_messages`.`note` AS `note`,`customer_messages`.`customer_id` AS `customer_id`,`customer_messages`.`user_created_id` AS `user_created_id`,`customer_messages`.`created_at` AS `created_at`,`customer_messages`.`updated_at` AS `updated_at`,`users`.`fullname` AS `fullname`,`users`.`avatar` AS `avatar`,`users`.`phone` AS `phone`,`users`.`email` AS `email` from (`customer_messages` join `users` on((`customer_messages`.`user_created_id` = `users`.`id`)));

-- ----------------------------
-- View structure for v_customer_reminders
-- ----------------------------
DROP VIEW IF EXISTS `v_customer_reminders`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_customer_reminders` AS select `customer_reminders`.`id` AS `id`,`customer_reminders`.`name` AS `name`,`customer_reminders`.`type` AS `type`,`customer_reminders`.`remind_at` AS `remind_at`,`customer_reminders`.`customer_id` AS `customer_id`,`customer_reminders`.`reminder_user_id` AS `reminder_user_id`,`customer_reminders`.`user_created_id` AS `user_created_id`,`customer_reminders`.`approveed` AS `approveed`,`customer_reminders`.`approved_note` AS `approved_note`,`customer_reminders`.`approved_time` AS `approved_time`,`customer_reminders`.`active` AS `active`,`customer_reminders`.`note` AS `note`,`customer_reminders`.`created_at` AS `created_at`,`customer_reminders`.`updated_at` AS `updated_at`,`uc`.`fullname` AS `uc_fullname`,`uc`.`avatar` AS `uc_avatar`,`ru`.`fullname` AS `ru_fullname`,`ru`.`avatar` AS `ru_avatar`,`ru`.`phone` AS `ru_phone`,`ru`.`email` AS `ru_email`,`ru`.`gender` AS `ru_gender`,`customers`.`state` AS `state`,`customers`.`code` AS `c_code`,`customers`.`display_name` AS `c_display_name`,`customers`.`name` AS `c_name`,`customers`.`phone` AS `c_phone`,`customers`.`email` AS `c_email`,`customers`.`zalo` AS `c_zalo`,`customers`.`first_payment` AS `c_first_payment`,`customers`.`second_payment` AS `c_second_payment`,`customers`.`listed_price` AS `c_listed_price`,`customers`.`final_price` AS `c_final_price`,`customers`.`sale_name` AS `c_sale_name`,`customers`.`sale_phone` AS `c_sale_phone`,`customers`.`sale_email` AS `c_sale_email`,`customers`.`product_price` AS `c_product_price`,`customers`.`guarantees` AS `c_guarantees` from (((`customer_reminders` join `users` `uc` on((`customer_reminders`.`user_created_id` = `uc`.`id`))) join `users` `ru` on((`customer_reminders`.`reminder_user_id` = `ru`.`id`))) join `customers` on((`customer_reminders`.`customer_id` = `customers`.`id`)));

-- ----------------------------
-- View structure for v_helper_reminders
-- ----------------------------
DROP VIEW IF EXISTS `v_helper_reminders`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_helper_reminders` AS select `helper_reminders`.`id` AS `id`,`helper_reminders`.`name` AS `name`,`helper_reminders`.`type` AS `type`,`helper_reminders`.`remind_at` AS `remind_at`,`helper_reminders`.`customer_id` AS `customer_id`,`helper_reminders`.`reminder_user_id` AS `reminder_user_id`,`helper_reminders`.`user_created_id` AS `user_created_id`,`helper_reminders`.`approveed` AS `approveed`,`helper_reminders`.`approved_note` AS `approved_note`,`helper_reminders`.`approved_time` AS `approved_time`,`helper_reminders`.`active` AS `active`,`helper_reminders`.`note` AS `note`,`helper_reminders`.`created_at` AS `created_at`,`helper_reminders`.`updated_at` AS `updated_at`,`uc`.`fullname` AS `uc_fullname`,`uc`.`avatar` AS `uc_avatar`,`ru`.`fullname` AS `ru_fullname`,`ru`.`avatar` AS `ru_avatar`,`ru`.`phone` AS `ru_phone`,`ru`.`email` AS `ru_email`,`ru`.`gender` AS `ru_gender`,`customers`.`state` AS `state`,`customers`.`code` AS `c_code`,`customers`.`display_name` AS `c_display_name`,`customers`.`name` AS `c_name`,`customers`.`phone` AS `c_phone`,`customers`.`email` AS `c_email`,`customers`.`zalo` AS `c_zalo`,`customers`.`first_payment` AS `c_first_payment`,`customers`.`second_payment` AS `c_second_payment`,`customers`.`listed_price` AS `c_listed_price`,`customers`.`final_price` AS `c_final_price`,`customers`.`sale_name` AS `c_sale_name`,`customers`.`sale_phone` AS `c_sale_phone`,`customers`.`sale_email` AS `c_sale_email`,`customers`.`product_price` AS `c_product_price`,`customers`.`guarantees` AS `c_guarantees` from (((`helper_reminders` join `users` `uc` on((`helper_reminders`.`user_created_id` = `uc`.`id`))) join `users` `ru` on((`helper_reminders`.`reminder_user_id` = `ru`.`id`))) join `customers` on((`helper_reminders`.`customer_id` = `customers`.`id`)));

SET FOREIGN_KEY_CHECKS = 1;
