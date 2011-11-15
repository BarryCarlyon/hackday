-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 09, 2009 at 05:39 PM
-- Server version: 5.0.45
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `lsrfm_com_cmsb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

CREATE TABLE `config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL COMMENT '0 / FALSE || 1 / TRUE',
  PRIMARY KEY  (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `config` (`config_name`, `config_value`) VALUES 
('site_online', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `sessions`
-- 

CREATE TABLE `sessions` (
  `session_id` varchar(100) NOT NULL default '',
  `ip_address` varchar(15) NOT NULL,
  `last_page` varchar(255) NOT NULL,
  `http_host` varchar(100) NOT NULL,
  `last_active` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `session_tracker`
-- 

CREATE TABLE `session_tracker` (
  `track_id` int(11) NOT NULL auto_increment,
  `ip_address` varchar(15) NOT NULL,
  `this_page` varchar(100) NOT NULL,
  `last_page` varchar(255) NOT NULL,
  `http_host` varchar(100) NOT NULL,
  `tos` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `get` varchar(255) NOT NULL,
  PRIMARY KEY  (`track_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

