SET @sName = 'bx_developer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_nav_item' AND `name`IN ('primary', 'collapsed', 'active_api');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_nav_item', @sName, 'primary', '1', '', 0, 'switcher', '_bx_dev_nav_txt_sys_items_primary', '_bx_dev_nav_txt_items_primary', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_nav_item', @sName, 'collapsed', '1', '', 0, 'switcher', '_bx_dev_nav_txt_sys_items_collapsed', '_bx_dev_nav_txt_items_collapsed', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_nav_item', @sName, 'active_api', '1', '', 0, 'switcher', '_bx_dev_nav_txt_sys_items_active_api', '_bx_dev_nav_txt_items_active_api', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_nav_item_add' AND input_name IN ('primary', 'collapsed', 'active_api');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_add', 'primary', 2147483647, 1, 17),
('bx_developer_nav_item_add', 'collapsed', 2147483647, 1, 17),
('bx_developer_nav_item_add', 'active_api', 2147483647, 1, 18);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_nav_item_edit' AND input_name IN ('primary', 'collapsed', 'active_api');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_edit', 'primary', 2147483647, 1, 18),
('bx_developer_nav_item_edit', 'collapsed', 2147483647, 1, 18),
('bx_developer_nav_item_edit', 'active_api', 2147483647, 1, 19);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_bp_block' AND `name`='active_api';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_bp_block', @sName, 'active_api', '1', '', 0, 'switcher', '_bx_dev_bp_txt_sys_block_active_api', '_bx_dev_bp_txt_block_active_api', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_bp_block_edit' AND `input_name`='active_api';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_bp_block_edit', 'active_api', 2147483647, 1, 16);
