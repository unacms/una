
-- TABLES
DROP TABLE IF EXISTS `bx_ads_entries`, `bx_ads_categories_types`, `bx_ads_categories`, `bx_ads_interested_track`, `bx_ads_licenses`, `bx_ads_licenses_deleted`, `bx_ads_covers`, `bx_ads_files`, `bx_ads_photos`, `bx_ads_photos_resized`, `bx_ads_videos`, `bx_ads_videos_resized`, `bx_ads_cmts`, `bx_ads_cmts_notes`, `bx_ads_reviews`, `bx_ads_votes`, `bx_ads_votes_track`, `bx_ads_reactions`, `bx_ads_reactions_track`, `bx_ads_views_track`, `bx_ads_meta_keywords`, `bx_ads_meta_locations`, `bx_ads_meta_mentions`, `bx_ads_reports`, `bx_ads_reports_track`, `bx_ads_favorites_track`, `bx_ads_scores`, `bx_ads_scores_track`, `bx_ads_polls`, `bx_ads_polls_answers`, `bx_ads_polls_answers_votes`, `bx_ads_polls_answers_votes_track`, `bx_ads_offers`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` LIKE 'bx_ads_%';
DELETE FROM `sys_storage_tokens` WHERE `object` LIKE 'bx_ads_%';

DELETE FROM `sys_objects_transcoder` WHERE `object` LIKE 'bx_ads_%';
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` LIKE 'bx_ads_%';
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` LIKE 'bx_ads_%';

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_ads';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_ads';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_ads';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_ads_%';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_ads%';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` LIKE 'bx_ads%';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_ads';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_ads';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_ads';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_ads';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_ads';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` LIKE 'bx_ads%';

DELETE FROM `sys_content_info_grids` WHERE `object` LIKE 'bx_ads%';

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_ads';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_ads';
