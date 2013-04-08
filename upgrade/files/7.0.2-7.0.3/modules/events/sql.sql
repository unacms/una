
DROP TABLE `[db_prefix]forum_report`;

ALTER TABLE `[db_prefix]forum` ADD `forum_order` int(11) NOT NULL default '0' AFTER `forum_type`;
ALTER TABLE `[db_prefix]forum_cat` ADD `cat_expanded` tinyint(4) NOT NULL default '0';
ALTER TABLE `[db_prefix]forum_post` ADD `hidden` tinyint(4) NOT NULL default '0';
ALTER TABLE `[db_prefix]forum_topic` ADD `topic_hidden` tinyint(4) NOT NULL default '0';


CREATE TABLE IF NOT EXISTS `[db_prefix]forum_actions_log` (
  `user_name` varchar(32) NOT NULL default '',
  `id` int(11) NOT NULL default '0',
  `action_name` varchar(32) NOT NULL default '',
  `action_when` int(11) NOT NULL default '0',
  KEY `action_when` (`action_when`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]forum_attachments` (
  `att_hash` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `att_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `att_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `att_when` int(11) NOT NULL,
  `att_size` int(11) NOT NULL,
  `att_downloads` int(11) NOT NULL,
  PRIMARY KEY (`att_hash`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]forum_signatures` (
  `user` varchar(32) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `when` int(11) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'events' AND `version` = '1.0.2';

