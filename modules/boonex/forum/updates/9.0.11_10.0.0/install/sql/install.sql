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


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`=@sName;
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(@sName, @sName, 'bx_forum_cmts', 1, 5000, 1000, 2, 5, 3, 'tail', 1, 'bottom', 0, 0, 1, -3, 1, 'cmt', 'page.php?i=view-discussion&id={object_id}', '', '', '', 'bx_forum_discussions', 'id', 'author', 'title', 'comments', 'BxForumCmts', 'modules/boonex/forum/classes/BxForumCmts.php');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`=@sName;
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
(@sName, 'bx_forum_views_track', '86400', '1', 'bx_forum_discussions', 'id', 'author', 'views', '', '');


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN (@sName, 'bx_forum_reactions');
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
(@sName, 'bx_forum_votes', 'bx_forum_votes_track', '604800', '1', '1', '0', '1', 'bx_forum_discussions', 'id', 'author', 'rate', 'votes', '', ''),
('bx_forum_reactions', 'bx_forum_reactions', 'bx_forum_reactions_track', '604800', '1', '1', '1', '1', 'bx_forum_discussions', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`=@sName;
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
(@sName, @sName, 'bx_forum_scores', 'bx_forum_scores_track', '604800', '0', 'bx_forum_discussions', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`=@sName;
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
(@sName, 'bx_forum_favorites_track', '1', '1', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'favorites', '', '');


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`=@sName;
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
(@sName, '1', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'featured', '', '');
