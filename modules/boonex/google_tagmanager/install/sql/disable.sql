
-- OPTIONS
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_googletagman';


-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name` IN('bx_googletagman_track_js', 'bx_googletagman_track_no_js');


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_googletagman';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_googletagman' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;
