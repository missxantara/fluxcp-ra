CREATE TABLE `cp_acclinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_aid` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `main` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;