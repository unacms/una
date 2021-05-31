-- Studio page and widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_help_tours';

-- Main Tables
DROP TABLE IF EXISTS `bx_help_tours`;
DROP TABLE IF EXISTS `bx_help_tours_items`;
DROP TABLE IF EXISTS `bx_help_tours_track_views`;

-- Help Tours page blocks
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_help_tours';

-- ACCOUNT REMOVAL ALERT
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_help_tours_account_delete' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;