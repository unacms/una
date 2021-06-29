SET @sName = 'bx_developer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_bp_block' AND `name` IN ('async', 'submenu', 'tabs', 'hidden_on');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_bp_block', @sName, 'async', '', '', 0, 'select', '_bx_dev_bp_txt_sys_block_async', '_bx_dev_bp_txt_block_async', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_bp_block', @sName, 'submenu', '', '', 0, 'select', '_bx_dev_bp_txt_sys_block_submenu', '_bx_dev_bp_txt_block_submenu', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_bp_block', @sName, 'tabs', '1', '', 0, 'switcher', '_bx_dev_bp_txt_sys_block_tabs', '_bx_dev_bp_txt_block_tabs', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_bp_block', @sName, 'hidden_on', '', '', 0, 'select_multiple', '_bx_dev_bp_txt_sys_block_hidden_on', '_bx_dev_bp_txt_block_hidden_on', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_bp_block_edit' AND `input_name` IN ('async', 'submenu', 'tabs', 'hidden_on');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_bp_block_edit', 'async', 2147483647, 1, 7),
('bx_developer_bp_block_edit', 'submenu', 2147483647, 1, 8),
('bx_developer_bp_block_edit', 'tabs', 2147483647, 1, 9),
('bx_developer_bp_block_edit', 'hidden_on', 2147483647, 1, 10);

DELETE FROM `sys_objects_form` WHERE `object`='bx_developer_pgt_keys';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_developer_pgt_keys', @sName, '_bx_dev_pgt_txt_keys_form', '', '', 'do_submit', 'sys_localization_keys', 'ID', '', '', '', 0, 1, 'BxDevFormView', 'modules/boonex/developer/classes/BxDevFormView.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_developer_pgt_keys';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_developer_pgt_keys_add', @sName, 'bx_developer_pgt_keys', '_bx_dev_pgt_txt_keys_display_add', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_pgt_keys';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_pgt_keys', @sName, 'language', '', '', 0, 'select', '_bx_dev_pgt_txt_sys_keys_language', '_bx_dev_pgt_txt_keys_language', '_bx_dev_pgt_inf_keys_language', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_pgt_keys', @sName, 'category', '', '', 0, 'select', '_bx_dev_pgt_txt_sys_keys_category', '_bx_dev_pgt_txt_keys_category', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_pgt_err_keys_category', 'Int', '', 0, 0),
('bx_developer_pgt_keys', @sName, 'content', '', '', 0, 'textarea', '_bx_dev_pgt_txt_sys_keys_content', '_bx_dev_pgt_txt_keys_content', '_bx_dev_pgt_inf_keys_content', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_pgt_err_keys_content', 'Xss', '', 0, 0),
('bx_developer_pgt_keys', @sName, 'replace', '1', '', 0, 'switcher', '_bx_dev_pgt_txt_sys_keys_replace', '_bx_dev_pgt_txt_keys_replace', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_pgt_keys', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_developer_pgt_keys', @sName, 'do_submit', '_bx_dev_pgt_btn_keys_submit', '', 0, 'submit', '_bx_dev_pgt_btn_sys_keys_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_developer_pgt_keys', @sName, 'do_cancel', '_bx_dev_pgt_btn_keys_cancel', '', 0, 'button', '_bx_dev_pgt_btn_sys_keys_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_pgt_keys_add';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_pgt_keys_add', 'language', 2147483647, 1, 1),
('bx_developer_pgt_keys_add', 'category', 2147483647, 1, 2),
('bx_developer_pgt_keys_add', 'content', 2147483647, 1, 3),
('bx_developer_pgt_keys_add', 'replace', 2147483647, 1, 4),
('bx_developer_pgt_keys_add', 'controls', 2147483647, 1, 5),
('bx_developer_pgt_keys_add', 'do_submit', 2147483647, 1, 6),
('bx_developer_pgt_keys_add', 'do_cancel', 2147483647, 1, 7);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_developer_pgt_manage' AND `type`='independent' AND `name`='add_keys';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_developer_pgt_manage', 'independent', 'add_keys', '_bx_dev_pgt_btn_manage_gl_add_keys', '', 0, 0);


