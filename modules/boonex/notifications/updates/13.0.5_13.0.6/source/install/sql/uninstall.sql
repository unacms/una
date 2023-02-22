SET @sName = 'bx_notifications';


DROP TABLE IF EXISTS `bx_notifications_events`, `bx_notifications_events2users`, `bx_notifications_read`;
DROP TABLE IF EXISTS `bx_notifications_handlers`, `bx_notifications_settings`, `bx_notifications_settings2users`;
DROP TABLE IF EXISTS `bx_notifications_queue`;


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;
