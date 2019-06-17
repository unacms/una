SET @sName = 'bx_forum';


-- TABLES
ALTER TABLE `bx_forum_discussions` CHANGE `text` `text` mediumtext NOT NULL;
ALTER TABLE `bx_forum_discussions` CHANGE `text_comments` `text_comments` mediumtext NOT NULL;
ALTER TABLE `bx_forum_discussions` CHANGE `allow_view_to` `allow_view_to` varchar(16) NOT NULL DEFAULT '3';

CREATE TABLE IF NOT EXISTS `bx_forum_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_reactions_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);
