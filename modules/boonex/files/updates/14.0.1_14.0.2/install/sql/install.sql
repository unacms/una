-- TABLES
CREATE TABLE IF NOT EXISTS `bx_files_sounds_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_files_videos_resized` (
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


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_files_sounds_resized', 'bx_files_videos_resized');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_files_sounds_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_files_sounds_resized', 'allow-deny', '{audio}', '', 0, 0, 0, 0, 0, 0),
('bx_files_videos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_files_videos_resized', 'allow-deny', '{video}', '', 0, 0, 0, 0, 0, 0);

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_files_sounds_mp3', 'bx_files_videos_poster', 'bx_files_videos_poster_preview', 'bx_files_videos_mp4', 'bx_files_videos_mp4_hd');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_files_sounds_mp3', 'bx_files_sounds_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '0', '0', '0', 'BxDolTranscoderAudio', ''),

('bx_files_videos_poster', 'bx_files_videos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_files_videos_poster_preview', 'bx_files_videos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_files_videos_mp4', 'bx_files_videos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_files_videos_mp4_hd', 'bx_files_videos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_files_sounds_mp3', 'bx_files_videos_poster_preview', 'bx_files_videos_poster', 'bx_files_videos_mp4', 'bx_files_videos_mp4_hd');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_files_sounds_mp3', 'Mp3', 'a:0:{}', 0),

('bx_files_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_files_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_files_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_files_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_files_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0);
