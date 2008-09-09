CREATE TABLE IF NOT EXISTS `cp_redeemlog` (
  `id` int(11) unsigned NOT NULL default '0',
  `nameid` int(11) unsigned NOT NULL default '0',
  `quantity` int(11) unsigned NOT NULL default '0',
  `refine` tinyint(3) unsigned NOT NULL default '0',
  `attribute` tinyint(4) unsigned NOT NULL default '0',
  `card0` smallint(11) unsigned NOT NULL default '0',
  `card1` smallint(11) unsigned NOT NULL default '0',
  `card2` smallint(11) unsigned NOT NULL default '0',
  `card3` smallint(11) unsigned NOT NULL default '0',
  `cost` int(11) unsigned NOT NULL,
  `account_id` int(11) unsigned NOT NULL,
  `char_id` int(11) unsigned NOT NULL,
  `redeemed` tinyint(1) unsigned NOT NULL,
  `redemption_date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Log of redeemed donation items.';