
-- Settings

DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_chat_plus';

-- Menu

DELETE FROM `sys_menu_items` WHERE `module` = 'bx_chat_plus' OR `name` = 'chat-plus';

-- Page

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_chat_plus';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_chat_plus' OR `object` IN('bx_chat_plus_chat');

-- Injections

DELETE FROM `sys_injections` WHERE `name` = 'bx_chat_plus';

