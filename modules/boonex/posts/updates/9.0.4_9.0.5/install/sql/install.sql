-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:14:"bx_posts_html5";}', `values`='a:2:{s:15:"bx_posts_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_posts_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='pictures';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_posts', 'bx_posts_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_posts', '_bx_posts', 'bx_posts', 'added', 'edited', 'deleted', '', ''),
('bx_posts_cmts', '_bx_posts_cmts', 'bx_posts', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_posts';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_posts', 'bx_posts_administration', 'id', '', ''),
('bx_posts', 'bx_posts_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_posts';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_posts', 'bx_posts', 'bx_posts', '_bx_posts_search_extended', 1, '', ''),
('bx_posts_cmts', 'bx_posts_cmts', 'bx_posts', '_bx_posts_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
