-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 12 2016 г., 18:08
-- Версия сервера: 5.6.22-log
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `gateway`
--

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE `payment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `service_id` VARCHAR(512) NULL DEFAULT NULL,
  `session_id` VARCHAR(512) NULL DEFAULT NULL,
  `partner_id` VARCHAR(512) NULL DEFAULT NULL,
  `partner_addr` TEXT NULL,
  `order_id` VARCHAR(512) NULL DEFAULT NULL,
  `account` VARCHAR(512) NULL DEFAULT NULL,
  `amount` DECIMAL(10,2) NULL DEFAULT NULL,
  `comission` DECIMAL(10,2) NULL DEFAULT NULL,
  `status` INT(11) NULL DEFAULT '10',
  `created_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `IDX_session_id` (`session_id`(255))
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `mobile_money` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date_time` VARCHAR(512) NULL DEFAULT NULL,
  `type_pay` VARCHAR(512) NULL DEFAULT NULL,
  `service_id` VARCHAR(512) NULL DEFAULT NULL,
  `account` VARCHAR(512) NULL DEFAULT NULL,
  `payment_id` VARCHAR(512) NULL DEFAULT NULL,
  `amount` DECIMAL(10,2) NULL DEFAULT NULL,
  `phone` VARCHAR(50) NULL DEFAULT NULL,
  `status` INT(11) NULL DEFAULT '10',
  `created_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `cities` (
  `id` INT(11) NOT NULL,
  `region_id` INT(11) NOT NULL DEFAULT '0',
  `name` VARCHAR(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `commissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `commission_id` INT(11) NOT NULL,
  `service_id` INT(11) NOT NULL DEFAULT '0',
  `amount_from` VARCHAR(50) NOT NULL DEFAULT '0',
  `amount_to` VARCHAR(50) NOT NULL DEFAULT '0',
  `date_from` VARCHAR(50) NULL DEFAULT '0',
  `date_to` VARCHAR(50) NULL DEFAULT '0',
  `fixed` VARCHAR(50) NOT NULL DEFAULT '0',
  `min` VARCHAR(50) NOT NULL DEFAULT '0',
  `max` VARCHAR(50) NOT NULL DEFAULT '0',
  `percent` VARCHAR(50) NOT NULL DEFAULT '0',
  `time_from` VARCHAR(50) NOT NULL DEFAULT '0',
  `time_to` VARCHAR(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;




CREATE TABLE `groups` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(50) NULL DEFAULT NULL,
  `slug` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `regions` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `services` (
  `id` INT(11) NOT NULL,
  `amount_min` DECIMAL(10,2) NULL DEFAULT NULL,
  `amount_max` DECIMAL(10,2) NULL DEFAULT NULL,
  `name` VARCHAR(150) NULL DEFAULT NULL,
  `description` TEXT NULL,
  `service_key` VARCHAR(150) NULL DEFAULT NULL,
  `account_info` VARCHAR(350) NULL DEFAULT NULL,
  `template` VARCHAR(350) NULL DEFAULT NULL,
  `type` VARCHAR(50) NULL DEFAULT NULL,
  `image` VARCHAR(250) NULL DEFAULT NULL,
  `priority` VARCHAR(250) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `fields` (
  `id` INT(11) NOT NULL,
  `service_id` INT(11) NULL DEFAULT NULL,
  `sign` VARCHAR(150) NULL DEFAULT NULL,
  `name` VARCHAR(150) NULL DEFAULT NULL,
  `hint` VARCHAR(250) NULL DEFAULT NULL,
  `heading` TEXT NULL,
  `mask` VARCHAR(150) NULL DEFAULT NULL,
  `filter` VARCHAR(350) NULL DEFAULT NULL,
  `regex` VARCHAR(150) NULL DEFAULT NULL,
  `format` VARCHAR(150) NULL DEFAULT NULL,
  `order_index` INT(11) NULL DEFAULT NULL,
  `min` INT(11) NULL DEFAULT NULL,
  `max` INT(11) NULL DEFAULT NULL,
  `notice_label` TEXT NULL,
  `payment_error` TEXT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `service_region` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `region_id` INT(11) NOT NULL DEFAULT '0',
  `service_id` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `service_cities` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `city_id` INT(11) NOT NULL DEFAULT '0',
  `service_id` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `service_groups` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_id` INT(11) NOT NULL DEFAULT '0',
  `service_id` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
  AUTO_INCREMENT=1110
;

CREATE TABLE `service_keys` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `service_id` INT(11) NULL DEFAULT NULL,
  `service_key` VARCHAR(250) NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `service_id` (`service_id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;







CREATE TABLE `cities_en` (
  `id` INT(11) NOT NULL,
  `region_id` INT(11) NOT NULL DEFAULT '0',
  `name` VARCHAR(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `cities_ua` (
  `id` INT(11) NOT NULL,
  `region_id` INT(11) NOT NULL DEFAULT '0',
  `name` VARCHAR(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `fields_en` (
  `id` INT(11) NOT NULL,
  `service_id` INT(11) NULL DEFAULT NULL,
  `sign` VARCHAR(150) NULL DEFAULT NULL,
  `name` VARCHAR(150) NULL DEFAULT NULL,
  `hint` VARCHAR(250) NULL DEFAULT NULL,
  `heading` TEXT NULL,
  `mask` VARCHAR(150) NULL DEFAULT NULL,
  `filter` VARCHAR(350) NULL DEFAULT NULL,
  `regex` VARCHAR(150) NULL DEFAULT NULL,
  `format` VARCHAR(150) NULL DEFAULT NULL,
  `order_index` INT(11) NULL DEFAULT NULL,
  `min` INT(11) NULL DEFAULT NULL,
  `max` INT(11) NULL DEFAULT NULL,
  `notice_label` TEXT NULL,
  `payment_error` TEXT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `fields_ua` (
  `id` INT(11) NOT NULL,
  `service_id` INT(11) NULL DEFAULT NULL,
  `sign` VARCHAR(150) NULL DEFAULT NULL,
  `name` VARCHAR(150) NULL DEFAULT NULL,
  `hint` VARCHAR(250) NULL DEFAULT NULL,
  `heading` TEXT NULL,
  `mask` VARCHAR(150) NULL DEFAULT NULL,
  `filter` VARCHAR(350) NULL DEFAULT NULL,
  `regex` VARCHAR(150) NULL DEFAULT NULL,
  `format` VARCHAR(150) NULL DEFAULT NULL,
  `order_index` INT(11) NULL DEFAULT NULL,
  `min` INT(11) NULL DEFAULT NULL,
  `max` INT(11) NULL DEFAULT NULL,
  `notice_label` TEXT NULL,
  `payment_error` TEXT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `groups_en` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(50) NULL DEFAULT NULL,
  `slug` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `groups_ua` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(50) NULL DEFAULT NULL,
  `slug` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `regions_en` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `regions_ua` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;


CREATE TABLE `services_en` (
  `id` INT(11) NOT NULL,
  `amount_min` DECIMAL(10,2) NULL DEFAULT NULL,
  `amount_max` DECIMAL(10,2) NULL DEFAULT NULL,
  `name` VARCHAR(150) NULL DEFAULT NULL,
  `description` TEXT NULL,
  `service_key` VARCHAR(150) NULL DEFAULT NULL,
  `account_info` VARCHAR(350) NULL DEFAULT NULL,
  `template` VARCHAR(350) NULL DEFAULT NULL,
  `type` VARCHAR(50) NULL DEFAULT NULL,
  `image` VARCHAR(250) NULL DEFAULT NULL,
  `priority` VARCHAR(250) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE `services_ua` (
  `id` INT(11) NOT NULL,
  `amount_min` DECIMAL(10,2) NULL DEFAULT NULL,
  `amount_max` DECIMAL(10,2) NULL DEFAULT NULL,
  `name` VARCHAR(150) NULL DEFAULT NULL,
  `description` TEXT NULL,
  `service_key` VARCHAR(150) NULL DEFAULT NULL,
  `account_info` VARCHAR(350) NULL DEFAULT NULL,
  `template` VARCHAR(350) NULL DEFAULT NULL,
  `type` VARCHAR(50) NULL DEFAULT NULL,
  `image` VARCHAR(250) NULL DEFAULT NULL,
  `priority` VARCHAR(250) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;








/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
