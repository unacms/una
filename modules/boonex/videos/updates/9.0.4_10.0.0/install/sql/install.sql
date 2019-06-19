-- TABLES
ALTER TABLE `bx_videos_entries` CHANGE `allow_view_to` `allow_view_to` varchar(16) NOT NULL DEFAULT '3';
ALTER TABLE `bx_videos_entries` CHANGE `status` `status` enum('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_videos_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_reactions_track` (
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


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_videos_video_mp4_hd', 'bx_videos_video_webm');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_videos_video_mp4_hd', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_videos";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_videos_video_mp4_hd', 'bx_videos_video_webm');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_videos_video_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_videos' AND `name`='labels';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_videos', 'bx_videos', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_videos_reactions';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_videos_reactions', 'bx_videos_reactions', 'bx_videos_reactions_track', '604800', '1', '1', '1', '1', 'bx_videos_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');
