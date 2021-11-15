-- TABLES
ALTER TABLE `bx_videos_entries` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_videos_cmts_notes` (
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


CREATE TABLE IF NOT EXISTS `bx_videos_embeds_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(64) NOT NULL,
  `params` text NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

DELETE FROM `bx_videos_embeds_providers` WHERE `object`='oembed' AND `module`='bx_videos';
INSERT INTO `bx_videos_embeds_providers` (`object`, `module`, `params`, `class_name`, `class_file`) VALUES
('oembed', 'bx_videos', '', 'BxVideosEmbedProviderOEmbed', 'modules/boonex/videos/classes/BxVideosEmbedProviderOEmbed.php');


-- FORMS
UPDATE `sys_objects_form` SET `params`='a:1:{s:14:"checker_helper";s:25:"BxVideosFormCheckerHelper";}' WHERE `object`='bx_videos';

UPDATE `sys_form_inputs` SET `checker_func`='UploadVideoAvail' WHERE `object`='bx_videos' AND `name`='videos';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_videos' AND `name` IN ('video_source', 'video_embed');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_videos', 'bx_videos', 'video_source', 'upload', '#!bx_videos_source', 0, 'radio_set', '_bx_videos_form_entry_input_sys_video_source', '_bx_videos_form_entry_input_video_source', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_videos', 'bx_videos', 'video_embed', '', '', 0, 'custom', '_bx_videos_form_entry_input_sys_video_embed', '_bx_videos_form_entry_input_video_embed', '', '', 1, 0, 0, '', '', '', 'EmbedVideoAvail', '', '_bx_videos_form_entry_input_video_embed_error', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_videos_entry_add', 'bx_videos_entry_edit') AND `input_name` IN ('video_source', 'video_embed');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_videos_entry_add', 'video_source', 2147483647, 1, 4),
('bx_videos_entry_add', 'video_embed', 2147483647, 1, 5),

('bx_videos_entry_edit', 'video_source', 2147483647, 1, 4),
('bx_videos_entry_edit', 'video_embed', 2147483647, 1, 5);


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_videos_source';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_videos_source', '_bx_videos_pre_lists_source', 'bx_videos', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_videos_source';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_videos_source', 'upload', 0, '_bx_videos_source_upload', ''),
('bx_videos_source', 'embed', 1, '_bx_videos_source_embed', '');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_videos_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_videos_notes', 'bx_videos', 'bx_videos_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-video&id={object_id}', '', 'bx_videos_entries', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');