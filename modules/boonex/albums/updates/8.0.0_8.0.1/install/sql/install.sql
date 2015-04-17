ALTER TABLE `bx_albums_files2albums` ADD `exif` text NOT NULL;

CREATE TABLE IF NOT EXISTS `bx_albums_meta_keywords_media_camera` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"h";s:3:"180";}' WHERE `transcoder_object`='bx_albums_browse';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"h";s:3:"180";s:10:"force_type";s:3:"jpg";}' WHERE `transcoder_object`='bx_albums_video_poster_browse'; 
