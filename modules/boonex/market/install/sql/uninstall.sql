
-- TABLES
DROP TABLE IF EXISTS `bx_market_products`, `bx_market_licenses`, `bx_market_files`, `bx_market_files2products`, `bx_market_downloads_track`, `bx_market_photos`, `bx_market_photos2products`, `bx_market_photos_resized`, `bx_market_cmts`, `bx_market_votes`, `bx_market_votes_track`, `bx_market_views_track`, `bx_market_meta_keywords`, `bx_market_meta_locations`, `bx_market_reports`, `bx_market_reports_track`;

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_market_files', 'bx_market_photos', 'bx_market_photos_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_market_files', 'bx_market_photos', 'bx_market_photos_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_market_preview', 'bx_market_cover', 'bx_market_screenshot', 'bx_market_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_market_preview', 'bx_market_cover', 'bx_market_screenshot', 'bx_market_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_market_preview', 'bx_market_cover', 'bx_market_screenshot', 'bx_market_gallery');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_market';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_market';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_market';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit', 'bx_market_entry_view', 'bx_market_entry_view_full', 'bx_market_entry_delete');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_market';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_market_cats');

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_market';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_market';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `Name` = 'bx_market';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `Name` = 'bx_market';

-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_market';
