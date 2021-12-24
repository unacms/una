SET @sName = 'bx_markerio';


-- SETTINGS
DELETE FROM `tot`, `toc`, `to` USING `sys_options_types` AS `tot` LEFT JOIN `sys_options_categories` AS `toc` ON `tot`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `tot`.`name`=@sName;


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction`=`sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module`=@sName;
DELETE FROM `sys_acl_actions` WHERE `Module`=@sName;


-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name`=@sName;
