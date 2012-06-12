#
#  dbtables.sql
#
#  Simplifies the task of creating all the database tables
#  used by the login system.
#
#  Can be run from command prompt by typing:
#
#  mysql -u yourusername -D yourdatabasename < dbtables.sql
#
#  That's with dbtables.sql in the mysql bin directory, but
#  you can just include the path to dbtables.sql and that's
#  fine too.
#
#  Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
#  Last Updated: August 13, 2004
#

#
#  Table structure for users table
#
-- phpMyAdmin SQL Dump
-- version 3.4.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2011 at 04:14 PM
-- Server version: 5.0.77
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dibs1_shipping`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(32) default NULL,
  `user_id` varchar(32) default NULL,
  `user_level` int(10) unsigned NOT NULL,
  `email` varchar(50) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


#
#  Table structure for active users table
#
DROP TABLE IF EXISTS users_active;

CREATE TABLE users_active (
 username varchar(30) primary key,
 timestamp int(11) unsigned not null
);


#
#  Table structure for active guests table
#
DROP TABLE IF EXISTS users_active_guests;

CREATE TABLE users_active_guests (
 ip varchar(15) primary key,
 timestamp int(11) unsigned not null
);


#
#  Table structure for banned users table
#
DROP TABLE IF EXISTS users_banned;

CREATE TABLE users_banned (
 username varchar(30) primary key,
 timestamp int(11) unsigned not null
);


