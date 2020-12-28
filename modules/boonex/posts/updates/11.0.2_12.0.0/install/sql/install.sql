-- TABLES
CREATE TABLE IF NOT EXISTS `bx_posts_sounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_sounds_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_cmts_notes` (
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

CREATE TABLE IF NOT EXISTS `bx_posts_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_posts_sounds', 'bx_posts_sounds_resized');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_posts_sounds', @sStorageEngine, '', 360, 2592000, 3, 'bx_posts_sounds', 'allow-deny', 'mp3,m4a,m4b,wma,wav,3gp', '', 0, 0, 0, 0, 0, 0),
('bx_posts_sounds_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_posts_sounds_resized', 'allow-deny', 'mp3,m4a,m4b,wma,wav,3gp', '', 0, 0, 0, 0, 0, 0);

DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_posts_sounds_mp3';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_sounds_mp3', 'bx_posts_sounds_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_sounds";}', 'no', '0', '0', '0', 'BxDolTranscoderAudio', '');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:4:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}' WHERE `transcoder_object`='bx_posts_gallery_photos' AND `filter`='Resize';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:4:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}' WHERE `transcoder_object`='bx_posts_gallery_files' AND `filter`='Resize';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_posts_sounds_mp3' AND `filter`='Mp3';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_sounds_mp3', 'Mp3', 'a:0:{}', 0);


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:2:{i:0;s:21:"bx_posts_videos_html5";i:1;s:28:"bx_posts_videos_record_video";}', `values`='a:3:{s:22:"bx_posts_videos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_posts_videos_html5";s:25:"_sys_uploader_html5_title";s:28:"bx_posts_videos_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_posts' AND `name`='videos';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='sounds';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'sounds', 'a:1:{i:0;s:21:"bx_posts_sounds_html5";}', 'a:2:{s:22:"bx_posts_sounds_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_posts_sounds_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_posts_form_entry_input_sys_sounds', '_bx_posts_form_entry_input_sounds', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_posts_entry_add', 'bx_posts_entry_edit') AND `input_name`='sounds';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'sounds', 2147483647, 1, 8),
('bx_posts_entry_edit', 'sounds', 2147483647, 1, 9);


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_posts_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_posts_notes', 'bx_posts', 'bx_posts_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', '', '', 'bx_posts_posts', 'id', 'author', 'title', 'comments', '', '');


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_posts', `object_comment`='bx_posts_notes' WHERE `name`='bx_posts';


-- FAVORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_posts_favorites_lists' WHERE `name`='bx_posts';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_posts' WHERE `name`='bx_posts';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_posts' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
