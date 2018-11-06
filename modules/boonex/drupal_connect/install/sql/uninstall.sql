
DROP TABLE IF EXISTS `bx_drupal_accounts`;

-- Forms

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_drupal';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_drupal';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_drupal';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'bx_drupal_login';

-- Studio page and widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_drupal';

