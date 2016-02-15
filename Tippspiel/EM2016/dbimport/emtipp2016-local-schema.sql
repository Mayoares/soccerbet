-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 06. September 2014 um 04:26
-- Server Version: 5.1.41
-- PHP-Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `emtipp2016`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_championtipps`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_championtipps` (
  `user` varchar(100) NOT NULL,
  `team` varchar(3) NOT NULL,
  `rank` int(1) NOT NULL,
  `score` int(3) NOT NULL,
  PRIMARY KEY (`user`,`rank`),
  KEY `team` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_finalmatchtipps`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_finalmatchtipps` (
  `user` varchar(30) NOT NULL,
  `matchnr` int(2) NOT NULL,
  `teamX` varchar(3) DEFAULT NULL,
  `teamY` varchar(3) DEFAULT NULL,
  `goalsX` int(1) DEFAULT NULL,
  `goalsY` int(1) DEFAULT NULL,
  `winner` int(1) DEFAULT NULL,
  `goaldiff` int(1) DEFAULT NULL,
  `score` int(2) DEFAULT NULL,
  UNIQUE KEY `matchnr+user` (`matchnr`,`user`),
  KEY `teamX` (`teamX`,`teamY`),
  KEY `teamY` (`teamY`),
  KEY `user` (`user`,`teamX`,`teamY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_groupcitations`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_groupcitations` (
  `group` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `citation` varchar(1000) COLLATE latin1_german1_ci NOT NULL,
  `author` varchar(100) COLLATE latin1_german1_ci NOT NULL,
  KEY `citation` (`citation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_groupmatchtipps`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_groupmatchtipps` (
  `user` varchar(30) NOT NULL,
  `matchnr` int(2) NOT NULL,
  `goalsX` int(2) NOT NULL,
  `goalsY` int(2) NOT NULL,
  `winner` tinyint(1) DEFAULT NULL,
  `goaldiff` smallint(1) DEFAULT NULL,
  `score` int(2) DEFAULT NULL,
  UNIQUE KEY `user_2` (`user`,`matchnr`),
  KEY `matchnr` (`matchnr`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_groupranktipps`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_groupranktipps` (
  `user` varchar(30) NOT NULL,
  `team` varchar(3) NOT NULL,
  `rank` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`user`,`team`),
  KEY `team` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_matches`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_matches` (
  `matchnr` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(1) NOT NULL,
  `team1` varchar(3) DEFAULT NULL,
  `team2` varchar(3) DEFAULT NULL,
  `matchdate` varchar(10) NOT NULL,
  `matchtime` time NOT NULL,
  `matchtype` varchar(20) NOT NULL,
  `evaluationDone` varchar(1) NOT NULL DEFAULT 'F',
  PRIMARY KEY (`matchnr`),
  KEY `group` (`group`),
  KEY `team1` (`team1`),
  KEY `team2` (`team2`),
  KEY `matchtime` (`matchtime`),
  KEY `matchtype` (`matchtype`),
  KEY `matchdate` (`matchdate`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_teams`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_teams` (
  `name` varchar(50) DEFAULT NULL,
  `shortname` varchar(3) NOT NULL,
  `logofile` varchar(200) DEFAULT NULL,
  `group` varchar(1) NOT NULL,
  PRIMARY KEY (`shortname`),
  KEY `group` (`group`),
  KEY `shortname` (`shortname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_topscorertipps`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_topscorertipps` (
  `user` varchar(100) NOT NULL,
  `topscorer` varchar(100) DEFAULT NULL,
  `team` varchar(3) NOT NULL,
  `score` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`),
  KEY `team` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emtipp2016_users`
--

CREATE TABLE IF NOT EXISTS `emtipp2016_users` (
  `userid` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT 'nomail',
  `finalparticipantscore` int(3) NOT NULL,
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/* INSERT INTO `usr_web133_1`.`emtipp2016_users` (`userid`, `username`, `password`, `lastname`, `firstname`, `email`, `finalparticipantscore`) VALUES ('4572478', 'admin', 'Masterplan', 'Ich', 'Admin', 'nomail', '0'); */
INSERT INTO `emtipp2016_users` (`userid`, `username`, `password`, `lastname`, `firstname`, `email`, `finalparticipantscore`) VALUES ('4572478', 'admin', 'Masterplan', 'Ich', 'Admin', 'nomail', '0'); 
