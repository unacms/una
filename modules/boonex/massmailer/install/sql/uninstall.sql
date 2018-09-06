SET @sName = 'bx_massmailer';;

-- TABLES
DROP TABLE IF EXISTS `bx_massmailer_campaigns`, `bx_massmailer_segments`, `bx_massmailer_letters`;

-- STUDIO WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;