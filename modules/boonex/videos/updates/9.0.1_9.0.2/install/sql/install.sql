-- TABLES
CREATE TABLE IF NOT EXISTS `bx_videos_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_videos_poster';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_videos_poster', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_photos";}', 'no', 1, 2592000, 0, '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_videos_poster' AND `filter`='Resize';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_videos_poster', 'Resize', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0);


-- FORMS
UPDATE `sys_form_inputs` SET `required`='1', `checker_func`='Avail', `checker_error`='_bx_videos_form_entry_input_videos_error' WHERE `object`='bx_videos' AND `name`='videos';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3' WHERE `Name`='bx_videos';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_videos';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_videos', 'bx_videos', 'bx_videos_scores', 'bx_videos_scores_track', '604800', '0', 'bx_videos_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');
