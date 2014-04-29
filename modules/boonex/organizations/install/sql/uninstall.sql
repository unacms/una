
-- TABLE: PROFILES 

DROP TABLE IF EXISTS `bx_organizations_data`;
DELETE FROM sys_profiles WHERE `type` = 'bx_organizations';

-- TABLE: STORAGES & TRANSCODERS

-- TODO: delete picture files as well
DROP TABLE IF EXISTS `bx_organizations_pics`; 
DROP TABLE IF EXISTS `bx_organizations_pics_resized`;

-- TABLE: VIEWS

DROP TABLE IF EXISTS `bx_organizations_views_track`;

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_organizations';

DELETE FROM `sys_form_displays` WHERE `module` = 'bx_organizations';

DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_organizations';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_organization_add', 'bx_organization_delete', 'bx_organization_edit', 'bx_organization_edit_cover', 'bx_organization_view');

-- PRE-VALUES

DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_organizations';

DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_organizations_cats');

-- STUDIO PAGE & WIDGET

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_organizations';

