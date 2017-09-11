-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_market', 'bx_market_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_market', '_bx_market', 'bx_market', 'added', 'edited', 'deleted', '', ''),
('bx_market_cmts', '_bx_market_cmts', 'bx_market', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_market';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_market', 'bx_market_administration', 'id', '', ''),
('bx_market', 'bx_market_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_market';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_market', 'bx_market', 'bx_market', '_bx_market_search_extended', 1, '', ''),
('bx_market_cmts', 'bx_market_cmts', 'bx_market', '_bx_market_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
