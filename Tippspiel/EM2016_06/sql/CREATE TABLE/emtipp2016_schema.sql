CREATE TABLE IF NOT EXISTS `emtipp2016_championtipps` (
  `user` varchar(100) NOT NULL,
  `team` varchar(3) NOT NULL,
  `rank` int(1) NOT NULL,
  `score` int(3) NOT NULL,
  PRIMARY KEY (`user`,`rank`),
  KEY `team` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

--

CREATE TABLE IF NOT EXISTS `emtipp2016_groupcitations` (
  `group` varchar(20) COLLATE latin1_german1_ci NOT NULL,
  `citation` varchar(1000) COLLATE latin1_german1_ci NOT NULL,
  `author` varchar(100) COLLATE latin1_german1_ci NOT NULL,
  UNIQUE KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

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

--

CREATE TABLE IF NOT EXISTS `emtipp2016_groupranktipps` (
  `user` varchar(30) NOT NULL,
  `team` varchar(3) NOT NULL,
  `rank` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`user`,`team`),
  KEY `team` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

--

CREATE TABLE IF NOT EXISTS `emtipp2016_topscorertipps` (
  `user` varchar(100) NOT NULL,
  `topscorer` varchar(100) DEFAULT NULL,
  `team` varchar(3) NOT NULL,
  `score` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`),
  KEY `team` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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