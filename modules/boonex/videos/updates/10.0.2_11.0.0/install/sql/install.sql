-- TABLES
ALTER TABLE `bx_videos_photos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_videos_videos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_videos_media_resized` CHANGE `size` `size` bigint(20) NOT NULL;
