-- PAGES
UPDATE `sys_objects_page` SET `visible_for_levels`='128' WHERE `object`='bx_antispam_dnsbl';
UPDATE `sys_objects_page` SET `visible_for_levels`='128' WHERE `object`='bx_antispam_ip_table';
UPDATE `sys_objects_page` SET `visible_for_levels`='192' WHERE `object`='bx_antispam_block_log';


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='128' WHERE `object`='bx_antispam_grid_dnsbl';
UPDATE `sys_objects_grid` SET `visible_for_levels`='128' WHERE `object`='bx_antispam_grid_ip_table';
UPDATE `sys_objects_grid` SET `visible_for_levels`='192' WHERE `object`='bx_antispam_grid_block_log';
