SET @sName = 'bx_massmailer';;

-- TABLES
DROP TABLE IF EXISTS `bx_massmailer_campaigns`, `bx_massmailer_segments`, `bx_massmailer_letters`, `bx_massmailer_links`, `bx_massmailer_unsubscribe`;

-- STUDIO WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_massmailer_campaign_add', 'bx_massmailer_campaign_edit', 'bx_massmailer_campaign_send_test', 'bx_massmailer_campaign_send_all');
