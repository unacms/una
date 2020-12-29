-- TABLES
CREATE TABLE IF NOT EXISTS `bx_spaces_cmts_notes` (
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


-- PRE LISTS
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_spaces_roles';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_spaces_roles', '_bx_spaces_pre_lists_roles', 'bx_spaces', '1');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_spaces_roles';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('bx_spaces_roles', '0', 1, '_bx_spaces_role_regular', '', ''),
('bx_spaces_roles', '1', 2, '_bx_spaces_role_administrator', '', ''),
('bx_spaces_roles', '2', 3, '_bx_spaces_role_moderator', '', '');


-- COMMENTS
UPDATE `sys_objects_cmts` SET `ClassName`='BxSpacesCmts', `ClassFile`='modules/boonex/spaces/classes/BxSpacesCmts.php' WHERE `Name`='bx_spaces';

DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_spaces_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_spaces_notes', 'bx_spaces', 'bx_spaces_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', '', '', 'bx_spaces_data', 'id', 'author', 'space_name', 'comments', '', '');


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_spaces', `object_comment`='bx_spaces_notes' WHERE `name`='bx_spaces';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_spaces' WHERE `name`='bx_spaces';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_spaces' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
