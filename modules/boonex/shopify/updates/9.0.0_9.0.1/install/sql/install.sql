-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_shopify', 'bx_shopify_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_shopify', '_bx_shopify', 'bx_shopify', 'added', 'edited', 'deleted', '', ''),
('bx_shopify_cmts', '_bx_shopify_cmts', 'bx_shopify', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_shopify';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_shopify', 'bx_shopify_administration', 'id', '', ''),
('bx_shopify', 'bx_shopify_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_shopify';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_shopify', 'bx_shopify', 'bx_shopify', '_bx_shopify_search_extended', 1, '', ''),
('bx_shopify_cmts', 'bx_shopify_cmts', 'bx_shopify', '_bx_shopify_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
