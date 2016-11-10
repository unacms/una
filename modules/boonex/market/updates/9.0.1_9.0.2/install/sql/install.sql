-- TABLES
DROP TABLE IF EXISTS `bx_market_licenses_deleted`;
CREATE TABLE IF NOT EXISTS `bx_market_licenses_deleted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `product_id` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `order` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `license` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `type` varchar(16) collate utf8_unicode_ci NOT NULL default '',
  `domain` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `expired` int(11) unsigned NOT NULL default '0',
  `reason` varchar(16) collate utf8_unicode_ci NOT NULL default '',
  `deleted` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;