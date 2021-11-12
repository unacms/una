
-- Settings

DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_froala';

-- Editor

DELETE FROM `sys_objects_editor` WHERE `object` = 'bx_froala';

UPDATE `sys_options` SET `value` = 'sys_quill' WHERE `name` = 'sys_editor_default';

-- Injections

DELETE FROM `sys_injections` WHERE `name` IN('bx_froala');

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'bx_froala';
