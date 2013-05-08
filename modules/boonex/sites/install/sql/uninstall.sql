-- TABLES
DROP TABLE IF EXISTS `bx_sites_owners`;
DROP TABLE IF EXISTS `bx_sites_accounts`;
DROP TABLE IF EXISTS `bx_sites_payment_details`;
DROP TABLE IF EXISTS `bx_sites_payment_history`;
DROP TABLE IF EXISTS `bx_sites_settings`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_sites';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_sites';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_sites';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_sites_site_add', 'bx_sites_site_edit', 'bx_sites_site_confirm', 'bx_sites_site_pending', 'bx_sites_site_cancel', 'bx_sites_site_reactivate', 'bx_sites_site_suspended', 'bx_sites_site_delete', 'bx_sites_site_view');


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_sites';