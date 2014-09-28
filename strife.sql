/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : strife

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2014-09-28 12:11:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `account`
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `display` varchar(64) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(32) NOT NULL COMMENT 'md5 password',
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `type` varchar(255) NOT NULL DEFAULT '',
  `provider` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000002 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES ('1000001', 'Mellowz', 'admin@strife.com', '21232f297a57a5a743894a0e4a801fc3', 'enabled', 'player', '3');

-- ----------------------------
-- Table structure for `account_identity`
-- ----------------------------
DROP TABLE IF EXISTS `account_identity`;
CREATE TABLE `account_identity` (
  `uniqid` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `playerType` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `experience` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT 'None',
  `tutorialProgress` int(11) NOT NULL DEFAULT '0',
  `canCraft` tinyint(1) NOT NULL DEFAULT '0',
  `canPlayRanked` tinyint(1) NOT NULL DEFAULT '0',
  `lanBoosts` tinyint(1) DEFAULT '0',
  `ident_id` varchar(20) NOT NULL,
  PRIMARY KEY (`uniqid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of account_identity
-- ----------------------------
INSERT INTO `account_identity` VALUES ('572', '1000001', 'Administrator', 'enabled', 'player', '5', '0', 'None', '1052679', '0', '0', '0', '1000.001');

-- ----------------------------
-- Table structure for `account_info`
-- ----------------------------
DROP TABLE IF EXISTS `account_info`;
CREATE TABLE `account_info` (
  `account_id` int(11) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of account_info
-- ----------------------------
