SET @sName = 'bx_forum';


-- TABLES: entries
DROP TABLE IF EXISTS `bx_forum_discussions`, `bx_forum_categories`, `bx_forum_files`, `bx_forum_photos_resized`, `bx_forum_subscribers`, `bx_forum_cmts`, `bx_forum_views_track`, `bx_forum_votes`, `bx_forum_votes_track`, `bx_forum_meta_keywords`, `bx_forum_reports`, `bx_forum_reports_track`, `bx_forum_favorites_track`;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN ('bx_forum_files', 'bx_forum_files_cmts', 'bx_forum_photos_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN ('bx_forum_files', 'bx_forum_files_cmts', 'bx_forum_photos_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_forum_preview', 'bx_forum_preview_cmts');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_forum_preview', 'bx_forum_preview_cmts');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN ('bx_forum_preview', 'bx_forum_preview_cmts');


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_forum_entry_add', 'bx_forum_entry_edit', 'bx_forum_entry_view', 'bx_forum_entry_delete', 'bx_forum_search_full');


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module`=@sName;
DELETE FROM `sys_form_pre_values` WHERE `Key` IN ('bx_forum_cats');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN (@sName, 'bx_forum_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN (@sName);


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_forum';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;
