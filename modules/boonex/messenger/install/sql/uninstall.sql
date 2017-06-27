SET @sName = 'bx_messenger';

DROP TABLE IF EXISTS `bx_messenger_jots`;
DROP TABLE IF EXISTS `bx_messenger_lots`;
DROP TABLE IF EXISTS `bx_messenger_lots_types`;
DROP TABLE IF EXISTS `bx_messenger_users_info`;

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;