
-- TABLES
DROP TABLE IF EXISTS `bx_market_products`, `bx_market_subproducts`, `bx_market_licenses`, `bx_market_licenses_deleted`, `bx_market_files`, `bx_market_files2products`, `bx_market_downloads_track`, `bx_market_photos`, `bx_market_photos2products`, `bx_market_photos_resized`, `bx_market_cmts`, `bx_market_cmts_notes`, `bx_market_votes`, `bx_market_votes_track`, `bx_market_reactions`, `bx_market_reactions_track`, `bx_market_views_track`, `bx_market_meta_keywords`, `bx_market_meta_locations`, `bx_market_meta_mentions`, `bx_market_reports`, `bx_market_reports_track`, `bx_market_favorites_track`, `bx_market_favorites_lists`, `bx_market_scores`, `bx_market_scores_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_market_files', 'bx_market_photos', 'bx_market_photos_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_market_files', 'bx_market_photos', 'bx_market_photos_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_market_preview', 'bx_market_icon', 'bx_market_thumb', 'bx_market_cover', 'bx_market_screenshot', 'bx_market_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_market_preview', 'bx_market_icon', 'bx_market_thumb', 'bx_market_cover', 'bx_market_screenshot', 'bx_market_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_market_preview', 'bx_market_icon', 'bx_market_thumb', 'bx_market_cover', 'bx_market_screenshot', 'bx_market_gallery');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_market';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_market';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_market';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit', 'bx_market_entry_view', 'bx_market_entry_view_full', 'bx_market_entry_delete', 'bx_market_license_edit');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_market';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_market_cats', 'bx_market_prices', 'bx_market_durations');

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_market%';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` LIKE 'bx_market%';

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_market';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_market';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_market';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_market';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_market';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_market', 'bx_market_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_market');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_market';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_market';
