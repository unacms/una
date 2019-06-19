SET @sName = 'bx_timeline';


-- TABLES
ALTER TABLE `bx_timeline_events` CHANGE `object_id` `object_id` int(11) NOT NULL default '0';
ALTER TABLE `bx_timeline_events` CHANGE `object_privacy_view` `object_privacy_view` varchar(16) NOT NULL default '3';

CREATE TABLE IF NOT EXISTS `bx_timeline_cache` (
  `type` varchar(32) NOT NULL default '',
  `context_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY `item` (`type`, `context_id`, `profile_id`, `event_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_reactions_track` (
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


-- STORAGES, TRANSCODERS, UPLOADERS
UPDATE `sys_objects_uploader` SET `override_class_name`='BxTimelineUploaderSimpleAttach', `override_class_file`='modules/boonex/timeline/classes/BxTimelineUploaderSimpleAttach.php' WHERE `object`='bx_timeline_simple_photo';
UPDATE `sys_objects_uploader` SET `override_class_name`='BxTimelineUploaderSimpleAttach', `override_class_file`='modules/boonex/timeline/classes/BxTimelineUploaderSimpleAttach.php' WHERE `object`='bx_timeline_simple_video';

DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_timeline_html5_photo', 'bx_timeline_html5_video');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_html5_photo', 1, 'BxTimelineUploaderHTML5Attach', 'modules/boonex/timeline/classes/BxTimelineUploaderHTML5Attach.php'),
('bx_timeline_html5_video', 1, 'BxTimelineUploaderHTML5Attach', 'modules/boonex/timeline/classes/BxTimelineUploaderHTML5Attach.php');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_timeline_videos_mp4_hd', 'bx_timeline_videos_webm');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_videos_mp4_hd', 'bx_timeline_videos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', '');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:3:"300";}' WHERE `transcoder_object`='bx_timeline_photos_view';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:3:"600";}' WHERE `transcoder_object`='bx_timeline_photos_medium';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_timeline_videos_mp4_hd', 'bx_timeline_videos_webm');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('bx_timeline_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name`='published';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'published', '', '', 0, 'datetime', '_bx_timeline_form_post_input_sys_date_published', '_bx_timeline_form_post_input_date_published', '_bx_timeline_form_post_input_date_published_info', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0);

UPDATE `sys_form_inputs` SET `info`='' WHERE `object`='bx_timeline_post' AND `name`='date';
UPDATE `sys_form_inputs` SET `editable`='0' WHERE `object`='bx_timeline_post' AND `name`='object_privacy_view';
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:23:"bx_timeline_html5_photo";}', `values`='a:2:{s:24:"bx_timeline_simple_photo";s:26:"_sys_uploader_simple_title";s:23:"bx_timeline_html5_photo";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_timeline_post' AND `name`='photo';
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:23:"bx_timeline_html5_video";}', `values`='a:2:{s:24:"bx_timeline_simple_video";s:26:"_sys_uploader_simple_title";s:23:"bx_timeline_html5_video";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_timeline_post' AND `name`='video';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public', 'bx_timeline_post_add_profile', 'bx_timeline_post_edit') AND `input_name`='date';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public', 'bx_timeline_post_add_profile', 'bx_timeline_post_edit') AND `input_name`='published';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add', 'published', 192, 1, 6),
('bx_timeline_post_add_public', 'published', 192, 1, 6),
('bx_timeline_post_add_profile', 'published', 192, 1, 6),
('bx_timeline_post_edit', 'published', 192, 1, 5);


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_timeline_reactions';
INSERT INTO `sys_objects_vote`(`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_timeline_reactions', 'bx_timeline_reactions', 'bx_timeline_reactions_track', '604800', '1', '1', '1', '1', 'bx_timeline_events', 'id', 'object_id', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');
