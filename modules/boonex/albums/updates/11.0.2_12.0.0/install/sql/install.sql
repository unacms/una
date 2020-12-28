-- TABLES
CREATE TABLE IF NOT EXISTS `bx_albums_cmts_notes` (
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

CREATE TABLE IF NOT EXISTS `bx_albums_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:2:{i:0;s:15:"bx_albums_html5";i:1;s:22:"bx_albums_record_video";}', `values`='a:4:{s:16:"bx_albums_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_albums_html5";s:25:"_sys_uploader_html5_title";s:22:"bx_albums_record_video";s:32:"_sys_uploader_record_video_title";s:14:"bx_albums_crop";s:24:"_sys_uploader_crop_title";}' WHERE `object`='bx_albums' AND `name`='pictures';


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_albums_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_albums_notes', 'bx_albums', 'bx_albums_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', '', '', 'bx_albums_albums', 'id', 'author', 'title', 'comments', '', '');


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_albums', `object_comment`='bx_albums_notes' WHERE `name`='bx_albums';


-- FAFORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_albums_favorites_lists' WHERE `name`='bx_albums';
UPDATE `sys_objects_favorite` SET `trigger_field_author`='author' WHERE `name`='bx_albums_media';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_albums' WHERE `name`='bx_albums';
UPDATE `sys_objects_feature` SET `module`='bx_albums' WHERE `name`='bx_albums_media';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_albums' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
