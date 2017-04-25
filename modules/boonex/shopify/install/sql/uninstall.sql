
-- TABLES
DROP TABLE IF EXISTS `bx_shopify_entries`, `bx_shopify_settings`, `bx_shopify_cmts`, `bx_shopify_votes`, `bx_shopify_votes_track`, `bx_shopify_views_track`, `bx_shopify_meta_keywords`, `bx_shopify_meta_locations`, `bx_shopify_reports`, `bx_shopify_reports_track`, `bx_shopify_favorites_track`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_shopify_entry_add', 'bx_shopify_entry_edit', 'bx_shopify_entry_view', 'bx_shopify_entry_view_full', 'bx_shopify_entry_delete', 'bx_shopify_settings_edit');


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_shopify';
DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_shopify_cats');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` = 'bx_shopify';


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` = 'bx_shopify';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_shopify';


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_shopify';


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_shopify';


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_shopify';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_shopify';
