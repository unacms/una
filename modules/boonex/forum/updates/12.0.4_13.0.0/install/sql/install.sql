SET @sName = 'bx_forum';


-- TABLES
ALTER TABLE `bx_forum_discussions` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_forum_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_forum_view_photos';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_view_photos', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_photos";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_forum_view_photos';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_forum_view_photos', 'Resize',  'a:2:{s:1:"w";s:4:"2000";s:1:"h";s:4:"2000";}', '0');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_forum_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_forum_notes', @sName, 'bx_forum_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-discussion&id={object_id}', '', 'bx_forum_discussions', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');