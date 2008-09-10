CREATE TABLE IF NOT EXISTS `cp_xferlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from_account_id` int(10) unsigned NOT NULL,
  `target_account_id` int(10) unsigned NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `transfer_date` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM COMMENT='Credit transfer log.' AUTO_INCREMENT=1 ;