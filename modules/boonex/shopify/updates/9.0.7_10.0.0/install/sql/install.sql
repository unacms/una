-- TABLES
ALTER TABLE `bx_shopify_entries` CHANGE `allow_view_to` `allow_view_to` VARCHAR(16) NOT NULL DEFAULT '3';

CREATE TABLE IF NOT EXISTS `bx_shopify_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_reactions_track` (
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


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_shopify_reactions';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_shopify_reactions', 'bx_shopify_reactions', 'bx_shopify_reactions_track', '604800', '1', '1', '1', '1', 'bx_shopify_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');
