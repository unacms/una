
-- TABLE: PROFILES 

DROP TABLE IF EXISTS `bx_persons_data`;
DELETE FROM sys_profiles WHERE `type` = 'bx_persons';

-- TABLE: STORAGES & TRANSCODERS

-- TODO: delete picture files as well
DROP TABLE IF EXISTS `bx_persons_pictures`; 
DROP TABLE IF EXISTS `bx_persons_pictures_resized`;

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_persons';

DELETE FROM `sys_form_displays` WHERE `module` = 'bx_persons';

DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_persons';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_add';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_delete';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_edit';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view';

-- STUDIO PAGE & WIDGET

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_persons';

