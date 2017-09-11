-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldTitle`='text' WHERE `Name`='bx_polls';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_polls', 'bx_polls_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_polls', '_bx_polls', 'bx_polls', 'added', 'edited', 'deleted', '', ''),
('bx_polls_cmts', '_bx_polls_cmts', 'bx_polls', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_polls';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_polls', 'bx_polls_administration', 'id', '', ''),
('bx_polls', 'bx_polls_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_polls';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_polls', 'bx_polls', 'bx_polls', '_bx_polls_search_extended', 1, '', ''),
('bx_polls_cmts', 'bx_polls_cmts', 'bx_polls', '_bx_polls_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
