SET @sName = 'bx_forum';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name` IN ('submit_text', 'submit_block', 'draft_id');
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_forum_entry_add' AND `input_name` IN ('submit_text', 'submit_block', 'draft_id');
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_forum_entry_edit' AND `input_name` IN ('submit_text', 'submit_block', 'draft_id');

UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:14:"bx_forum_html5";}', `values`='a:2:{s:15:"bx_forum_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_forum_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`=@sName AND `name`='attachments';


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN (@sName, 'bx_forum_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
(@sName, '_bx_forum', @sName, 'added', 'edited', 'deleted', '', ''),
('bx_forum_cmts', '_bx_forum_cmts', @sName, 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`=@sName;
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
(@sName, @sName, 'id', '', 'a:1:{s:4:"sort";a:2:{s:5:"stick";s:4:"desc";s:12:"lr_timestamp";s:4:"desc";}}'),
(@sName, 'bx_forum_administration', 'id', '', ''),
(@sName, 'bx_forum_common', 'id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_forum';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_forum', 'bx_forum', 'bx_forum', '_bx_forum_search_extended', 1, '', ''),
('bx_forum_cmts', 'bx_forum_cmts', 'bx_forum', '_bx_forum_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');
