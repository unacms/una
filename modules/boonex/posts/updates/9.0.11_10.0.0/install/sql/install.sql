-- TABLES
ALTER TABLE `bx_posts_posts` CHANGE `text` `text` mediumtext NOT NULL;
ALTER TABLE `bx_posts_posts` CHANGE `allow_view_to` `allow_view_to` VARCHAR(16) NOT NULL DEFAULT '3';
ALTER TABLE `bx_posts_posts` CHANGE `status` `status` enum('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_posts_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_videos_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_reactions_track` (
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

CREATE TABLE IF NOT EXISTS `bx_posts_polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`text`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_polls_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_polls_answers_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_polls_answers_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);



-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_posts_videos', 'bx_posts_videos_resized', 'bx_posts_files');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_posts_videos', @sStorageEngine, '', 360, 2592000, 3, 'bx_posts_videos', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),
('bx_posts_videos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_posts_videos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),
('bx_posts_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_posts_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0);

UPDATE `sys_objects_transcoder` SET `source_params`='a:1:{s:6:"object";s:15:"bx_posts_covers";}' WHERE `object`='bx_posts_preview';
UPDATE `sys_objects_transcoder` SET `source_params`='a:1:{s:6:"object";s:15:"bx_posts_covers";}' WHERE `object`='bx_posts_gallery';
UPDATE `sys_objects_transcoder` SET `source_params`='a:1:{s:6:"object";s:15:"bx_posts_covers";}' WHERE `object`='bx_posts_cover';

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_posts_videos_poster', 'bx_posts_videos_poster_preview', 'bx_posts_videos_mp4', 'bx_posts_videos_mp4_hd', 'bx_posts_preview_files', 'bx_posts_gallery_files');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_videos_poster', 'bx_posts_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_posts_videos_poster_preview', 'bx_posts_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_posts_videos_mp4', 'bx_posts_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_posts_videos_mp4_hd', 'bx_posts_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_posts_preview_files', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_posts_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_posts_gallery_files', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_posts_files";}', 'no', '1', '2592000', '0', '', '');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"2000";}' WHERE `transcoder_object`='bx_posts_gallery_photos';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_posts_videos_poster_preview', 'bx_posts_videos_poster', 'bx_posts_videos_mp4', 'bx_posts_videos_mp4_hd', 'bx_posts_preview_files', 'bx_posts_gallery_files');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_posts_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_posts_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_posts_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_posts_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0),
('bx_posts_preview_files', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_posts_gallery_files', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name` IN ('videos', 'files', 'polls', 'attachments', 'disable_comments');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'videos', 'a:1:{i:0;s:21:"bx_posts_videos_html5";}', 'a:2:{s:22:"bx_posts_videos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_posts_videos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_posts_form_entry_input_sys_videos', '_bx_posts_form_entry_input_videos', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'files', 'a:1:{i:0;s:20:"bx_posts_files_html5";}', 'a:2:{s:21:"bx_posts_files_simple";s:26:"_sys_uploader_simple_title";s:20:"bx_posts_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_posts_form_entry_input_sys_files', '_bx_posts_form_entry_input_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'polls', '', '', 0, 'custom', '_bx_posts_form_entry_input_sys_polls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'attachments', '', '', 0, 'custom', '_bx_posts_form_entry_input_sys_attachments', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'disable_comments', '1', '', 0, 'switcher', '_bx_posts_form_entry_input_sys_disable_comments', '_bx_posts_form_entry_input_disable_comments', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:21:"bx_posts_photos_html5";}', `values`='a:2:{s:22:"bx_posts_photos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_posts_photos_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='pictures';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_posts_entry_add', 'bx_posts_entry_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_posts_entry_add', 'title', 2147483647, 1, 2),
('bx_posts_entry_add', 'cat', 2147483647, 1, 3),
('bx_posts_entry_add', 'text', 2147483647, 1, 4),
('bx_posts_entry_add', 'attachments', 2147483647, 1, 5),
('bx_posts_entry_add', 'pictures', 2147483647, 1, 6),
('bx_posts_entry_add', 'videos', 2147483647, 1, 7),
('bx_posts_entry_add', 'files', 2147483647, 1, 8),
('bx_posts_entry_add', 'polls', 2147483647, 1, 9),
('bx_posts_entry_add', 'covers', 2147483647, 1, 10),
('bx_posts_entry_add', 'allow_view_to', 2147483647, 1, 11),
('bx_posts_entry_add', 'location', 2147483647, 1, 12),
('bx_posts_entry_add', 'published', 192, 1, 13),
('bx_posts_entry_add', 'disable_comments', 192, 1, 14),
('bx_posts_entry_add', 'do_publish', 2147483647, 1, 15),

('bx_posts_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_posts_entry_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_posts_entry_edit', 'title', 2147483647, 1, 3),
('bx_posts_entry_edit', 'cat', 2147483647, 1, 4),
('bx_posts_entry_edit', 'text', 2147483647, 1, 5),
('bx_posts_entry_edit', 'attachments', 2147483647, 1, 6),
('bx_posts_entry_edit', 'pictures', 2147483647, 1, 7),
('bx_posts_entry_edit', 'videos', 2147483647, 1, 8),
('bx_posts_entry_edit', 'files', 2147483647, 1, 9),
('bx_posts_entry_edit', 'polls', 2147483647, 1, 10),
('bx_posts_entry_edit', 'covers', 2147483647, 1, 11),
('bx_posts_entry_edit', 'allow_view_to', 2147483647, 1, 12),
('bx_posts_entry_edit', 'location', 2147483647, 1, 13),
('bx_posts_entry_edit', 'disable_comments', 192, 1, 14),
('bx_posts_entry_edit', 'published', 192, 1, 15),
('bx_posts_entry_edit', 'do_submit', 2147483647, 1, 16);

DELETE FROM `sys_objects_form` WHERE `object`='bx_posts_poll';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_posts_poll', 'bx_posts', '_bx_posts_form_poll', '', '', 'do_submit', 'bx_posts_polls', 'id', '', '', 'a:1:{s:14:"checker_helper";s:28:"BxPostsFormPollCheckerHelper";}', 0, 1, 'BxPostsFormPoll', 'modules/boonex/posts/classes/BxPostsFormPoll.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_posts_poll';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_posts_poll_add', 'bx_posts', 'bx_posts_poll', '_bx_posts_form_poll_display_add', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts_poll';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_posts_poll', 'bx_posts', 'text', '', '', 0, 'textarea', '_bx_posts_form_poll_input_sys_text', '_bx_posts_form_poll_input_text', '', 1, 0, 3, '', '', '', 'Avail', '', '_bx_posts_form_poll_input_text_err', 'Xss', '', 1, 0),
('bx_posts_poll', 'bx_posts', 'answers', '', '', 0, 'custom', '_bx_posts_form_poll_input_sys_answers', '_bx_posts_form_poll_input_answers', '', 1, 0, 0, '', '', '', 'AvailAnswers', '', '_bx_posts_form_poll_input_answers_err', '', '', 1, 0),
('bx_posts_poll', 'bx_posts', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_posts_poll', 'bx_posts', 'do_submit', '_bx_posts_form_poll_input_do_submit', '', 0, 'submit', '_bx_posts_form_poll_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_posts_poll', 'bx_posts', 'do_cancel', '_bx_posts_form_poll_input_do_cancel', '', 0, 'button', '_bx_posts_form_poll_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_poll_add';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_posts_poll_add', 'text', 2147483647, 1, 1),
('bx_posts_poll_add', 'answers', 2147483647, 1, 2),
('bx_posts_poll_add', 'controls', 2147483647, 1, 3),
('bx_posts_poll_add', 'do_submit', 2147483647, 1, 4),
('bx_posts_poll_add', 'do_cancel', 2147483647, 1, 5);


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_posts_reactions', 'bx_posts_poll_answers');
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_posts_reactions', 'bx_posts_reactions', 'bx_posts_reactions_track', '604800', '1', '1', '1', '1', 'bx_posts_posts', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', ''),
('bx_posts_poll_answers', 'bx_posts_polls_answers_votes', 'bx_posts_polls_answers_votes_track', '604800', '1', '1', '0', '1', 'bx_posts_polls_answers', 'id', 'author_id', 'rate', 'votes', 'BxPostsVotePollAnswers', 'modules/boonex/posts/classes/BxPostsVotePollAnswers.php');
