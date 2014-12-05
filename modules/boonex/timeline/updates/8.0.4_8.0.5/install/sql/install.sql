CREATE TABLE IF NOT EXISTS `bx_timeline_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
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

CREATE TABLE IF NOT EXISTS `bx_timeline_videos_processed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
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

CREATE TABLE IF NOT EXISTS `bx_timeline_videos2events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `media_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `media` (`event_id`, `media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_timeline_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_timeline_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bx_timeline_photos_view`;
RENAME TABLE `bx_timeline_photos_preview` TO `bx_timeline_photos_processed`;
ALTER TABLE `bx_timeline_photos2events` CHANGE `photo_id` `media_id` INT( 11 ) NOT NULL DEFAULT '0';


-- STORAGES, TRANSCODERS, UPLOADERS
UPDATE `sys_objects_uploader` SET `object`='bx_timeline_simple_photo', `override_class_name`='BxTimelineUploaderSimplePhoto', `override_class_file`='modules/boonex/timeline/classes/BxTimelineUploaderSimplePhoto.php' WHERE `object`='bx_timeline_simple' LIMIT 1;

DELETE FROM `sys_objects_uploader` WHERE `object`='bx_timeline_simple_video' LIMIT 1;
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_simple_video', 1, 'BxTimelineUploaderSimpleVideo', 'modules/boonex/timeline/classes/BxTimelineUploaderSimpleVideo.php');


UPDATE `sys_objects_storage` SET `object`='bx_timeline_photos_processed', `table_files`='bx_timeline_photos_processed' WHERE `object`='bx_timeline_photos_preview' LIMIT 1 ;
DELETE FROM `sys_objects_storage` WHERE `object`='bx_timeline_photos_view' LIMIT 1;

DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_timeline_videos', 'bx_timeline_videos_processed');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_timeline_videos', 'Local', '', 360, 2592000, 3, 'bx_timeline_videos', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,divx,xvid,3gp,webm,jpg', '', 0, 0, 0, 0, 0, 0),
('bx_timeline_videos_processed', 'Local', '', 360, 2592000, 3, 'bx_timeline_videos_processed', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,divx,xvid,3gp,webm,jpg', '', 0, 0, 0, 0, 0, 0);


UPDATE `sys_objects_transcoder` SET `storage_object`='bx_timeline_photos_processed' WHERE `object`='bx_timeline_photos_preview' LIMIT 1;
UPDATE `sys_objects_transcoder` SET `storage_object`='bx_timeline_photos_processed' WHERE `object`='bx_timeline_photos_view' LIMIT 1;

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_timeline_videos_poster', 'bx_timeline_videos_mp4', 'bx_timeline_videos_webm');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_videos_poster', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_timeline_videos_mp4', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_timeline_videos_webm', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', '');


DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_timeline_videos_poster', 'bx_timeline_videos_mp4', 'bx_timeline_videos_webm');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('bx_timeline_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_timeline_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_timeline_videos_webm', 'Webm', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:4:"webm";}', 0);


-- FORMS
SET @sFrom = 'mod_tml_';
SET @sTo = 'bx_timeline_';
UPDATE `sys_objects_form` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_displays` SET `display_name`=REPLACE(`display_name`, @sFrom, @sTo), `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `display_name` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_inputs` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_display_inputs` SET `display_name`=REPLACE(`display_name`, @sFrom, @sTo) WHERE `display_name` LIKE CONCAT(@sFrom, '%');

UPDATE `sys_objects_form` SET `override_class_name`='BxTimelineFormPost', override_class_file='modules/boonex/timeline/classes/BxTimelineFormPost.php' WHERE `object`='bx_timeline_post';

UPDATE `sys_form_inputs` SET `db_pass` = '' WHERE `object`='bx_timeline_post' AND `name`='link' LIMIT 1;
UPDATE `sys_form_inputs` SET `db_pass` = '' WHERE `object`='bx_timeline_post' AND `name`='photo' LIMIT 1;


DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name` IN ('location', 'video', 'attachments');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'location', '', '', 0, 'custom', '_sys_form_input_sys_location', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_post', @sName, 'video', '', '', 0, 'files', '_bx_timeline_form_post_input_sys_video', '_bx_timeline_form_post_input_video', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_post', @sName, 'attachments', '', '', 0, 'custom', '_bx_timeline_form_post_input_sys_attachments', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_timeline_post_add' AND `input_name` IN ('location', 'link', 'photo', 'video', 'attachments', 'do_submit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add', 'location', 2147483647, 1, 5),
('bx_timeline_post_add', 'link', 2147483647, 1, 6),
('bx_timeline_post_add', 'photo', 2147483647, 1, 7),
('bx_timeline_post_add', 'video', 2147483647, 1, 8),
('bx_timeline_post_add', 'attachments', 2147483647, 1, 9),
('bx_timeline_post_add', 'do_submit', 2147483647, 1, 10);