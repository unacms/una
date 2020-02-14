-- TABLES
ALTER TABLE `bx_ads_covers` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_ads_files` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_ads_photos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_ads_photos_resized` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_ads_videos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_ads_videos_resized` CHANGE `size` `size` bigint(20) NOT NULL;


-- COMMENTS
UPDATE `sys_objects_cmts` SET `BaseUrl`='page.php?i=view-ad&id={object_id}' WHERE `Name` IN ('bx_ads', 'bx_ads_reviews');


-- REPORTS
UPDATE `sys_objects_report` SET `base_url`='page.php?i=view-ad&id={object_id}' WHERE `name`='bx_ads';


-- FAFORITES
UPDATE `sys_objects_favorite` SET `base_url`='page.php?i=view-ad&id={object_id}' WHERE `name`='bx_ads';


-- FEATURED
UPDATE `sys_objects_feature` SET `base_url`='page.php?i=view-ad&id={object_id}' WHERE `name`='bx_ads';
