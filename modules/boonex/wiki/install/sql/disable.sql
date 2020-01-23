
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

