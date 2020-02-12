
-- Settings

DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_wiki';

-- Wiki object

DELETE FROM `sys_objects_wiki` WHERE `object` = 'bx_wiki';

-- Permalinks

DELETE FROM `sys_permalinks` WHERE `standard` = 'r.php?_q=wiki/' AND `permalink` = 'wiki/';

-- Rewrite rules

DELETE FROM `sys_rewrite_rules` WHERE `preg` = '^wiki/(.*)$' OR `preg` = '^wiki-action/(.*)$';

-- Menu

DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_wiki';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_wiki';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_wiki' OR `set_name` LIKE 'bx_wiki%';

-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_wiki';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_wiki';

