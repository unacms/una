
-- Wiki object

DELETE FROM `sys_objects_wiki` WHERE `object` = 'bx_wiki';

-- Permalinks

DELETE FROM `sys_permalinks` WHERE `standard` = 'r.php?_q=wiki/' AND `permalink` = 'wiki/';

-- Rewrite rules

DELETE FROM `sys_rewrite_rules` WHERE `preg` = '^wiki/(.*)$';

