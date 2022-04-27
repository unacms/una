SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_cmts_notes` (
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


-- STORAGES, TRANSCODERS, UPLOADERS
UPDATE `sys_objects_storage` SET `ext_allow`='jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc' WHERE `object`='bx_timeline_videos';


DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_timeline_videos_poster', 'bx_timeline_videos_photo_preview' , 'bx_timeline_videos_photo_view', 'bx_timeline_videos_photo_big', 'bx_timeline_videos_poster_preview', 'bx_timeline_videos_poster_view', 'bx_timeline_videos_poster_big', 'bx_timeline_proxy_preview', 'bx_timeline_proxy_view', 'bx_timeline_proxy_big');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_videos_photo_preview', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '1', '2592000', '0', '', ''),
('bx_timeline_videos_photo_view', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '1', '2592000', '0', '', ''),
('bx_timeline_videos_photo_big', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '1', '2592000', '0', '', ''),
('bx_timeline_videos_poster_preview', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_timeline_videos_poster_view', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_timeline_videos_poster_big', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_timeline_proxy_preview', 'bx_timeline_videos_processed', 'Proxy', 'a:4:{s:6:"object";s:18:"bx_timeline_videos";s:5:"image";s:32:"bx_timeline_videos_photo_preview";s:12:"video_poster";s:33:"bx_timeline_videos_poster_preview";s:5:"video";a:4:{i:0;s:22:"bx_timeline_videos_mp4";i:1;s:25:"bx_timeline_videos_mp4_hd";i:2;s:30:"bx_timeline_videos_poster_view";i:3;s:29:"bx_timeline_videos_poster_big";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', ''),
('bx_timeline_proxy_view', 'bx_timeline_videos_processed', 'Proxy', 'a:4:{s:6:"object";s:18:"bx_timeline_videos";s:5:"image";s:29:"bx_timeline_videos_photo_view";s:12:"video_poster";s:30:"bx_timeline_videos_poster_view";s:5:"video";a:2:{i:0;s:22:"bx_timeline_videos_mp4";i:1;s:25:"bx_timeline_videos_mp4_hd";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', ''),
('bx_timeline_proxy_big', 'bx_timeline_videos_processed', 'Proxy', 'a:4:{s:6:"object";s:18:"bx_timeline_videos";s:5:"image";s:28:"bx_timeline_videos_photo_big";s:12:"video_poster";s:29:"bx_timeline_videos_poster_big";s:5:"video";a:2:{i:0;s:22:"bx_timeline_videos_mp4";i:1;s:25:"bx_timeline_videos_mp4_hd";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_timeline_videos_poster', 'bx_timeline_videos_photo_preview', 'bx_timeline_videos_photo_view', 'bx_timeline_videos_photo_big', 'bx_timeline_videos_poster_preview', 'bx_timeline_videos_poster_view', 'bx_timeline_videos_poster_big');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('bx_timeline_videos_photo_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"300";s:13:"square_resize";s:1:"1";}', '0'),
('bx_timeline_videos_photo_view', 'Resize', 'a:1:{s:1:"w";s:3:"480";}', '0'),
('bx_timeline_videos_photo_big', 'Resize', 'a:2:{s:1:"w";s:4:"1200";s:1:"h";s:4:"1200";}', '0'),
('bx_timeline_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_timeline_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"300";s:13:"square_resize";s:1:"1";}', 10),
('bx_timeline_videos_poster_view', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_timeline_videos_poster_big', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0);


-- FORMS
UPDATE `sys_objects_form` SET `form_attrs`='a:1:{s:8:"onsubmit";s:46:"return {js_object_post}.onFormPostSubmit(this)";}' WHERE `object`='bx_timeline_post';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name`='object_cf';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'object_cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public', 'bx_timeline_post_add_profile', 'bx_timeline_post_edit') AND `input_name`='object_cf';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add', 'object_cf', 2147483647, 1, 5),
('bx_timeline_post_add_public', 'object_cf', 2147483647, 1, 5),
('bx_timeline_post_add_profile', 'object_cf', 2147483647, 1, 5),
('bx_timeline_post_edit', 'object_cf', 2147483647, 1, 11);

UPDATE `sys_objects_form` SET `object`='bx_timeline_repost', `title`='_bx_timeline_form_repost', `override_class_name`='BxTimelineFormRepost', `override_class_file`='modules/boonex/timeline/classes/BxTimelineFormRepost.php' WHERE `object`='bx_timeline_repost_to';

DELETE FROM `sys_form_displays` WHERE `display_name` IN ('bx_timeline_repost_to_browse', 'bx_timeline_repost_with', 'bx_timeline_repost_to');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_timeline_repost_with', @sName, 'bx_timeline_repost', '_bx_timeline_form_repost_display_with', 0),
('bx_timeline_repost_to', @sName, 'bx_timeline_repost', '_bx_timeline_form_repost_display_to', 0);

DELETE FROM `sys_form_inputs` WHERE `object` IN ('bx_timeline_repost_to', 'bx_timeline_repost');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_repost', @sName, 'reposter_id', '', '', 0, 'hidden', '_bx_timeline_form_repost_input_sys_reposter_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_timeline_repost', @sName, 'owner_id', '', '', 0, 'hidden', '_bx_timeline_form_repost_input_sys_owner_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_timeline_repost', @sName, 'type', '', '', 0, 'hidden', '_bx_timeline_form_repost_input_sys_type', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_timeline_repost', @sName, 'action', '', '', 0, 'hidden', '_bx_timeline_form_repost_input_sys_action', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_timeline_repost', @sName, 'object_id', '', '', 0, 'hidden', '_bx_timeline_form_repost_input_sys_object_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_timeline_repost', @sName, 'search', '', '', 0, 'custom', '_bx_timeline_form_repost_input_sys_search', '_bx_timeline_form_repost_input_search', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost', @sName, 'list', '', '', 0, 'custom', '_bx_timeline_form_repost_input_sys_list', '_bx_timeline_form_repost_input_list', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost', @sName, 'text', '', '', 0, 'textarea', '_bx_timeline_form_repost_input_sys_text', '_bx_timeline_form_repost_input_text', '', 0, 0, 0, 'a:1:{s:12:"autocomplete";s:3:"off";}', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_timeline_repost', @sName, 'reposted', '', '', 0, 'custom', '_bx_timeline_form_repost_input_sys_reposted', '_bx_timeline_form_repost_input_reposted', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost', @sName, 'do_submit', '_bx_timeline_form_repost_input_do_submit', '', 0, 'submit', '_bx_timeline_form_repost_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost', @sName, 'do_cancel', '_bx_timeline_form_repost_input_do_cancel', '', 0, 'button', '_bx_timeline_form_repost_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_repost_to_browse', 'bx_timeline_repost_with', 'bx_timeline_repost_to');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_repost_with', 'reposter_id', 2147483647, 1, 1),
('bx_timeline_repost_with', 'owner_id', 2147483647, 1, 2),
('bx_timeline_repost_with', 'type', 2147483647, 1, 3),
('bx_timeline_repost_with', 'action', 2147483647, 1, 4),
('bx_timeline_repost_with', 'object_id', 2147483647, 1, 5),
('bx_timeline_repost_with', 'text', 2147483647, 1, 6),
('bx_timeline_repost_with', 'reposted', 2147483647, 1, 7),
('bx_timeline_repost_with', 'controls', 2147483647, 1, 8),
('bx_timeline_repost_with', 'do_submit', 2147483647, 1, 9),
('bx_timeline_repost_with', 'do_cancel', 2147483647, 1, 10),

('bx_timeline_repost_to', 'reposter_id', 2147483647, 1, 1),
('bx_timeline_repost_to', 'type', 2147483647, 1, 2),
('bx_timeline_repost_to', 'action', 2147483647, 1, 3),
('bx_timeline_repost_to', 'object_id', 2147483647, 1, 4),
('bx_timeline_repost_to', 'search', 2147483647, 1, 5),
('bx_timeline_repost_to', 'list', 2147483647, 1, 6),
('bx_timeline_repost_to', 'controls', 2147483647, 1, 7),
('bx_timeline_repost_to', 'do_submit', 2147483647, 1, 8),
('bx_timeline_repost_to', 'do_cancel', 2147483647, 1, 9);


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_timeline_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_timeline_notes', 'bx_timeline', 'bx_timeline_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', 'bx_timeline_events', 'id', 'object_owner_id', 'title', '', 'BxTemplCmtsNotes', '');


-- REPORTS
UPDATE `sys_objects_report` SET `object_comment`='bx_timeline_notes' WHERE `name`='bx_timeline';