DROP DATABASE IF EXISTS `[DATABASE_NAME]`;
CREATE DATABASE `[DATABASE_NAME]`;
USE `[DATABASE_NAME]`;
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(150) NOT NULL,
  `dt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
TRUNCATE `admin`;
DROP TABLE IF EXISTS `associations`;
-- CREATE TABLE `associations` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(100) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
TRUNCATE `associations`;
-- INSERT INTO `associations` (`id`, `name`) VALUES
-- (1,	'Amalgamated Traders Association'),
-- (2,	'United Butchers Association'),
-- (3,	'Jigawa Butchers Association'),
-- (4,	'Lagos State Butchers Association'),
-- (5,	'Irepodun Association');
-- DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `mname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL,
  `sex` char(1) NOT NULL,
  `dob` datetime NOT NULL,
  `m_status` char(1) NOT NULL,
  `img` varchar(500) NOT NULL,
  `assoc_id` int(11) NOT NULL,
  `dt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
TRUNCATE `members`;