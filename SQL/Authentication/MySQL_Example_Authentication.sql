# phpMyAdmin SQL Dump
# version 2.5.6
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jul 06, 2004 at 02:05 PM
# Server version: 3.23.56
# PHP Version: 4.3.2
# 
# Database : `adam_concerto`
# 

# --------------------------------------------------------

#
# Table structure for table `auth_db_user`
#

CREATE TABLE `auth_db_user` (
  `username` varchar(255) NOT NULL default '',
  `password` blob NOT NULL,
  PRIMARY KEY  (`username`)
) TYPE=MyISAM;
