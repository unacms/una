
-- Settings

DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_plyr';

-- Editor

DELETE FROM `sys_objects_player` WHERE `object` = 'bx_plyr';

UPDATE `sys_options` SET `value` = 'sys_html5' WHERE `name` = 'sys_player_default';
