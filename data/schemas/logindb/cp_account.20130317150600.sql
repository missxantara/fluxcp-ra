CREATE TABLE `cp_account` (
  `cp_aid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(23) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(39) NOT NULL DEFAULT '',
  `state` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`cp_aid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;