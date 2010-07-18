-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 18, 2010 at 02:55 PM
-- Server version: 5.0.90
-- PHP Version: 5.0.5

SET FOREIGN_KEY_CHECKS=0;
-- 
-- Database: `goodbaad`
-- 
DROP DATABASE `goodbaad`;
CREATE DATABASE `goodbaad` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `goodbaad`;

-- --------------------------------------------------------

-- 
-- Table structure for table `activation`
-- 

DROP TABLE IF EXISTS `activation`;
CREATE TABLE IF NOT EXISTS `activation` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `activation_key` varchar(40) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_users_verify_users1` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 AUTO_INCREMENT=119 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `followers_users`
-- 

DROP TABLE IF EXISTS `followers_users`;
CREATE TABLE IF NOT EXISTS `followers_users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `follower_id` int(11) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=131 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `invites`
-- 

DROP TABLE IF EXISTS `invites`;
CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(100) default NULL,
  `last_send` timestamp NULL default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL auto_increment,
  `title` varbinary(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=766 DEFAULT CHARSET=utf8 AUTO_INCREMENT=766 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `thirdparty`
-- 

DROP TABLE IF EXISTS `thirdparty`;
CREATE TABLE IF NOT EXISTS `thirdparty` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `identifier` varchar(150) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `topics`
-- 

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL auto_increment,
  `guid` varchar(40) default NULL,
  `freebase_id` varchar(200) default NULL,
  `title` varchar(45) default NULL,
  `handle` varchar(45) default NULL,
  `added` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `freebase_id` (`freebase_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2209 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2209 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `topics_tags`
-- 

DROP TABLE IF EXISTS `topics_tags`;
CREATE TABLE IF NOT EXISTS `topics_tags` (
  `id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) default NULL,
  `tag_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18902 DEFAULT CHARSET=utf8 AUTO_INCREMENT=18902 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `topics_users`
-- 

DROP TABLE IF EXISTS `topics_users`;
CREATE TABLE IF NOT EXISTS `topics_users` (
  `id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `opinion` enum('good','baad') default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_topics_users_users` (`user_id`),
  KEY `fk_topics_users_topics` (`topic_id`),
  KEY `user_id_index` (`user_id`),
  KEY `topic_id_index` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2903 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2903 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user_detail`
-- 

DROP TABLE IF EXISTS `user_detail`;
CREATE TABLE IF NOT EXISTS `user_detail` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `gender` enum('m','f') default NULL,
  `yob` year(4) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_user_detail_users` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(100) default NULL,
  `password` varchar(40) default NULL,
  `email` varchar(150) default NULL,
  `lastvisit` timestamp NULL default NULL,
  `created` timestamp NULL default NULL,
  `attempts` tinyint(1) default NULL,
  `active` enum('pending','active','dissabled') default 'pending',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=332 DEFAULT CHARSET=utf8 AUTO_INCREMENT=332 ;

SET FOREIGN_KEY_CHECKS=1;

