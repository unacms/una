-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_files', 'bx_files_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_files', '_bx_files', 'bx_files', 'added', 'edited', 'deleted', '', ''),
('bx_files_cmts', '_bx_files_cmts', 'bx_files', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_files';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_files', 'bx_files_administration', 'id', '', ''),
('bx_files', 'bx_files_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_files';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_files', 'bx_files', 'bx_files', '_bx_files_search_extended', 1, '', ''),
('bx_files_cmts', 'bx_files_cmts', 'bx_files', '_bx_files_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
