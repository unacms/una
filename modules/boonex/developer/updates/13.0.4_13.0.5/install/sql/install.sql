SET @sName = 'bx_developer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_nav_item' AND `name` IN ('hidden_on_pt', 'hidden_on_col', 'hidden_on_cxt');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_nav_item', @sName, 'hidden_on_pt', '', '', 0, 'select_multiple', '_bx_dev_nav_txt_sys_items_hidden_on_pt', '_bx_dev_nav_txt_items_hidden_on_pt', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 0, 0),
('bx_developer_nav_item', @sName, 'hidden_on_col', '', '', 0, 'select_multiple', '_bx_dev_nav_txt_sys_items_hidden_on_col', '_bx_dev_nav_txt_items_hidden_on_col', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 0, 0),
('bx_developer_nav_item', @sName, 'hidden_on_cxt', '', '', 0, 'text', '_bx_dev_nav_txt_sys_items_hidden_on_cxt', '_bx_dev_nav_txt_items_hidden_on_cxt', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_nav_item_add' AND `input_name` IN ('hidden_on_pt', 'hidden_on_col', 'hidden_on_cxt');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_add', 'hidden_on_pt', 2147483647, 1, 14),
('bx_developer_nav_item_add', 'hidden_on_col', 2147483647, 1, 14),
('bx_developer_nav_item_add', 'hidden_on_cxt', 2147483647, 1, 14);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_nav_item_edit' AND `input_name` IN ('hidden_on_pt', 'hidden_on_col', 'hidden_on_cxt');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_edit', 'hidden_on_pt', 2147483647, 1, 15),
('bx_developer_nav_item_edit', 'hidden_on_col', 2147483647, 1, 15),
('bx_developer_nav_item_edit', 'hidden_on_cxt', 2147483647, 1, 15);
