SET @sName = 'bx_timeline';


-- TABLES
ALTER TABLE `bx_timeline_photos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_timeline_photos_processed` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_timeline_videos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_timeline_videos_processed` CHANGE `size` `size` bigint(20) NOT NULL;
