-- TABLES
ALTER TABLE `bx_photos_photos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_photos_media_resized` CHANGE `size` `size` bigint(20) NOT NULL;

CREATE TABLE IF NOT EXISTS `bx_photos_meta_keywords_camera` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);
