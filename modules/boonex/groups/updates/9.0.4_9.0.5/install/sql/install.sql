-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_groups', 'bx_groups_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_groups', '_bx_groups', 'bx_groups', 'added', 'edited', 'deleted', '', ''),
('bx_groups_cmts', '_bx_groups_cmts', 'bx_groups', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_groups';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_groups', 'bx_groups_administration', 'td`.`id', '', ''),
('bx_groups', 'bx_groups_common', 'td`.`id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_groups';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_groups', 'bx_groups', 'bx_groups', '_bx_groups_search_extended', 1, '', ''),
('bx_groups_cmts', 'bx_groups_cmts', 'bx_groups', '_bx_groups_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
