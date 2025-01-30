SET @sName = 'bx_developer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_nav_menu' AND `name`='config_api';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_nav_menu', @sName, 'config_api', '', '', 0, 'textarea', '_bx_dev_nav_txt_sys_menus_config_api', '_bx_dev_nav_txt_menus_config_api', '', 0, 0, 0, '', '', '', '', '', '', 'XssHtml', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_developer_nav_menu_add', 'bx_developer_nav_menu_edit') AND `input_name`='config_api';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_menu_add', 'config_api', 2147483647, 1, 5),
('bx_developer_nav_menu_edit', 'config_api', 2147483647, 1, 6);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_nav_item' AND `name`='config_api';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_nav_item', @sName, 'config_api', '', '', 0, 'textarea', '_bx_dev_nav_txt_sys_items_config_api', '_bx_dev_nav_txt_items_config_api', '', 0, 0, 0, '', '', '', '', '', '', 'XssHtml', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_developer_nav_item_add', 'bx_developer_nav_item_edit') AND `input_name`='config_api';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_add', 'config_api', 2147483647, 1, 17),
('bx_developer_nav_item_edit', 'config_api', 2147483647, 1, 18);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_bp_page' AND `name`='config_api';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_bp_page', @sName, 'config_api', '', '', 0, 'textarea', '_bx_dev_bp_txt_sys_page_config_api', '_bx_dev_bp_txt_page_config_api', '', 0, 0, 0, '', '', '', '', '', '', 'XssHtml', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_bp_page_add' AND `input_name`='config_api';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_bp_page_add', 'config_api', 2147483647, 1, 7);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_bp_block' AND `name`='config_api';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_bp_block', @sName, 'config_api', '', '', 0, 'textarea', '_bx_dev_bp_txt_sys_block_config_api', '_bx_dev_bp_txt_block_config_api', '', 0, 0, 0, '', '', '', '', '', '', 'XssHtml', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_bp_block_edit' AND `input_name`='config_api';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_bp_block_edit', 'config_api', 2147483647, 1, 13);
