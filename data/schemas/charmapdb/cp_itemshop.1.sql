CREATE TABLE IF NOT EXISTS `cp_itemshop` (
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM COMMENT='Item shop';