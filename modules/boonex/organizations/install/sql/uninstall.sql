
-- TABLES

DROP TABLE IF EXISTS `bx_organizations_data`, `bx_organizations_pics`, `bx_organizations_pics_resized`, `bx_organizations_views_track`, `bx_organizations_meta_keywords`;

-- PROFILES

DELETE FROM sys_profiles WHERE `type` = 'bx_organizations';

-- STORAGES & TRANSCODERS

DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_organizations_pics', 'bx_organizations_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_organizations_pics', 'bx_organizations_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb');

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_organization_add', 'bx_organization_delete', 'bx_organization_edit', 'bx_organization_edit_cover', 'bx_organization_view', 'bx_organization_view_full');

-- PRE-VALUES

DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_organizations';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_organizations_cats');

-- STUDIO PAGE & WIDGET

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_organizations';

