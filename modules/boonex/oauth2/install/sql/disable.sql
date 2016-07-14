
-- GRID

DELETE FROM `sys_objects_grid` WHERE `object` = 'bx_oauth';
DELETE FROM `sys_grid_fields` WHERE `object` = 'bx_oauth';
DELETE FROM `sys_grid_actions` WHERE `object` = 'bx_oauth';

-- SETTINGS

DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_oauth';

-- PAGES

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_oauth';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_oauth' OR `object` IN ('bx_oauth_authorization');

