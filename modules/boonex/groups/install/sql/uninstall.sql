
-- TABLES

DROP TABLE IF EXISTS `bx_groups_data`, `bx_groups_pics`, `bx_groups_pics_resized`, `bx_groups_views_track`, `bx_groups_meta_keywords`, `bx_groups_fans`, `bx_groups_admins`, `bx_groups_votes`, `bx_groups_votes_track`, `bx_groups_reports`, `bx_groups_reports_track`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_groups';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_groups_pics', 'bx_groups_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_groups_pics', 'bx_groups_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_groups_icon', 'bx_groups_thumb', 'bx_groups_avatar', 'bx_groups_picture', 'bx_groups_cover', 'bx_groups_cover_thumb');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_groups_icon', 'bx_groups_thumb', 'bx_groups_avatar', 'bx_groups_picture', 'bx_groups_cover', 'bx_groups_cover_thumb');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_groups_icon', 'bx_groups_thumb', 'bx_groups_avatar', 'bx_groups_picture', 'bx_groups_cover', 'bx_groups_cover_thumb');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_groups';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_groups';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_groups';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_group_add', 'bx_group_delete', 'bx_group_edit', 'bx_group_edit_cover', 'bx_group_view', 'bx_group_view_full');

-- PRE-VALUES

DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_groups';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_groups_cats');

-- STUDIO PAGE & WIDGET

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_groups';

