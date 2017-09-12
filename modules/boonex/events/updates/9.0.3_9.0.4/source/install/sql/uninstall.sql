
-- TABLES
DROP TABLE IF EXISTS `bx_events_data`, `bx_events_intervals`, `bx_events_pics`, `bx_events_pics_resized`, `bx_events_cmts`, `bx_events_views_track`, `bx_events_meta_keywords`, `bx_events_meta_locations`, `bx_events_fans`, `bx_events_admins`, `bx_events_votes`, `bx_events_votes_track`, `bx_events_reports`, `bx_events_reports_track`, `bx_events_favorites_track`;

-- PROFILES
DELETE FROM sys_profiles WHERE `type` = 'bx_events';

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_events_pics', 'bx_events_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_events_pics', 'bx_events_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_events_icon', 'bx_events_thumb', 'bx_events_avatar', 'bx_events_picture', 'bx_events_cover', 'bx_events_cover_thumb', 'bx_events_gallery');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_events_icon', 'bx_events_thumb', 'bx_events_avatar', 'bx_events_picture', 'bx_events_cover', 'bx_events_cover_thumb', 'bx_events_gallery');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_events_icon', 'bx_events_thumb', 'bx_events_avatar', 'bx_events_picture', 'bx_events_cover', 'bx_events_cover_thumb', 'bx_events_gallery');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_events';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_events';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_events';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_event_add', 'bx_event_delete', 'bx_event_edit', 'bx_event_edit_cover', 'bx_event_view', 'bx_event_view_full', 'bx_event_invite');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_events';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_events_cats');

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_events', 'bx_events_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_events');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_events';

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_events';
