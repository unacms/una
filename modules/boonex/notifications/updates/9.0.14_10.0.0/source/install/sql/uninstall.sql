SET @sName = 'bx_notifications';


DROP TABLE IF EXISTS `bx_notifications_events`, `bx_notifications_events2users`, `bx_notifications_handlers`, `bx_notifications_settings`, `bx_notifications_settings2users`, `bx_notifications_queue`;


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;
