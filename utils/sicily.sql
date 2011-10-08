-- phpMyAdmin SQL Dump
-- version 3.3.7deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 03, 2011 at 12:35 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-1ubuntu9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sicily`
--

-- --------------------------------------------------------

--
-- Table structure for table `contests`
--

CREATE TABLE IF NOT EXISTS `contests` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `starttime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `during` time NOT NULL DEFAULT '05:00:00',
  `freeze_during` int(10) NOT NULL DEFAULT '0',
  `perm` enum('admin','user','manager','temp') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `ipbind` enum('free','bind') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'free',
  `authtype` enum('free','password','internal','bound') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'free',
  `pwd` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `owner` int(11) NOT NULL,
  `addrepos` tinyint(1) NOT NULL DEFAULT '1',
  `information` text NOT NULL,
  PRIMARY KEY (`cid`),
  KEY `addrepos` (`addrepos`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contest_problems`
--

CREATE TABLE IF NOT EXISTS `contest_problems` (
  `cpid` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`cpid`,`cid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contest_status`
--

CREATE TABLE IF NOT EXISTS `contest_status` (
  `csid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `cpid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`csid`,`cid`),
  KEY `cid` (`cid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `judge`
--

CREATE TABLE IF NOT EXISTS `judge` (
  `jid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique ID assigned to judge',
  `judgename` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lasttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`jid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE IF NOT EXISTS `problems` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `time_limit` int(11) NOT NULL DEFAULT '1',
  `memory_limit` int(11) NOT NULL DEFAULT '32768',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `input` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `output` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `sample_input` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sample_output` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `accepted` int(11) NOT NULL DEFAULT '0',
  `submissions` int(11) NOT NULL DEFAULT '0',
  `special_judge` tinyint(1) NOT NULL DEFAULT '0',
  `has_framework` tinyint(1) NOT NULL DEFAULT '0',
  `author` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hint` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avail` tinyint(4) NOT NULL DEFAULT '1',
  `cid` int(11) NOT NULL DEFAULT '0',
  `rate_tot` int(11) NOT NULL DEFAULT '0',
  `rate_count` int(11) NOT NULL DEFAULT '0',
  `dataversion` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  KEY `avail` (`avail`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=1 AUTO_INCREMENT=1000 ;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE IF NOT EXISTS `queue` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL DEFAULT '0',
  `hold` tinyint(4) NOT NULL DEFAULT '0',
  `server_id` int(4) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0',
  `cpid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ranklist`
--

CREATE TABLE IF NOT EXISTS `ranklist` (
  `cid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `accepted` int(11) NOT NULL DEFAULT '0',
  `submissions` int(11) NOT NULL DEFAULT '1',
  `ac_time` int(11) NOT NULL DEFAULT '0',
  KEY `cid` (`cid`),
  KEY `cid_2` (`cid`,`uid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `rate` int(3) NOT NULL DEFAULT '0',
  KEY `uid` (`uid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE IF NOT EXISTS `registration` (
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `restrict_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Relationship between user and contest';

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `language` enum('C','C++','Pascal','Java') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'C',
  `status` enum('Judging','Compiling','Waiting','Running','Accepted','Wrong Answer','Compile Error','Runtime Error','Time Limit Exceeded','Memory Limit Exceeded','Output Limit Exceeded','Presentation Error','Restrict Function','Out of Contest Time','Other') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Waiting',
  `run_time` float NOT NULL DEFAULT '0',
  `run_memory` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `failcase` int(11) NOT NULL DEFAULT '-1',
  `contest` int(11) NOT NULL DEFAULT '0',
  `codelength` int(11) DEFAULT '0',
  `public` int(2) NOT NULL DEFAULT '0',
  `sourcecode` text NOT NULL,
  `compilelog` text NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `uid_2` (`uid`,`status`),
  KEY `contest` (`contest`),
  KEY `uid_3` (`uid`,`contest`),
  KEY `pid_2` (`pid`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `solved` int(10) unsigned NOT NULL DEFAULT '0',
  `submissions` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `list` blob NOT NULL,
  `perm` set('admin','user','manager','temp') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `netid` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `applynetid` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `authcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `authtime` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `signature` varchar(150) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=1 AUTO_INCREMENT=1 ;


INSERT INTO  `user` (
`uid` ,
`username` ,
`password` ,
`email` ,
`address` ,
`solved` ,
`submissions` ,
`reg_time` ,
`perm` ,
`phone` ,
`netid` ,
`applynetid` ,
`authcode` ,
`authtime` ,
`nickname` ,
`signature`
)
VALUES (
NULL ,  'root', MD5(  'admin' ) ,  'admin@soj.me',  'Heaven',  '0',  '0',  '0000-00-00 00:00:00',  'admin,user,manager', NULL ,  '',  '',  '',  '',  'Admin', 'I am the god'
);
