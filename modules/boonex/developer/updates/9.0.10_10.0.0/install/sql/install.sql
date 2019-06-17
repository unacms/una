-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_forms_prevalue' AND `name`='Data';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_forms_prevalue', @sName, 'Data', '', '', 0, 'textarea', '_bx_dev_frm_txt_sys_prevalues_data', '_bx_dev_frm_txt_prevalues_data', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_developer_forms_prevalue_add', 'bx_developer_forms_prevalue_edit') AND `input_name`='Data';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_forms_prevalue_add', 'Data', 2147483647, 1, 5),
('bx_developer_forms_prevalue_edit', 'Data', 2147483647, 1, 6);

UPDATE `sys_objects_form` SET `title`='_bx_dev_frm_txt_search_forms_form' WHERE `object`='bx_developer_forms_search_form';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_nav_item' AND `name` IN ('addon', 'submenu_popup', 'visibility_custom', 'hidden_on');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_nav_item', @sName, 'addon', '', '', 0, 'textarea', '_bx_dev_nav_txt_sys_items_addon', '_bx_dev_nav_txt_items_addon', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_nav_item', @sName, 'submenu_popup', '1', '', 0, 'switcher', '_bx_dev_nav_txt_sys_items_submenu_popup', '_bx_dev_nav_txt_items_submenu_popup', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_nav_item', @sName, 'visibility_custom', '', '', 0, 'textarea', '_bx_dev_nav_txt_sys_items_visibility_custom', '_bx_dev_nav_txt_items_visibility_custom', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_nav_item', @sName, 'hidden_on', '', '', 0, 'select_multiple', '_bx_dev_nav_txt_sys_items_hidden_on', '_bx_dev_nav_txt_items_hidden_on', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_nav_item_add' AND `input_name` IN ('addon', 'submenu_popup', 'visibility_custom', 'hidden_on');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_add', 'addon', 2147483647, 1, 10),
('bx_developer_nav_item_add', 'submenu_popup', 2147483647, 1, 12),
('bx_developer_nav_item_add', 'visibility_custom', 2147483647, 1, 13),
('bx_developer_nav_item_add', 'hidden_on', 2147483647, 1, 14);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_nav_item_edit' AND `input_name` IN ('addon', 'submenu_popup', 'visibility_custom', 'hidden_on');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_nav_item_edit', 'addon', 2147483647, 1, 11),
('bx_developer_nav_item_edit', 'submenu_popup', 2147483647, 1, 13),
('bx_developer_nav_item_edit', 'visibility_custom', 2147483647, 1, 14),
('bx_developer_nav_item_edit', 'hidden_on', 2147483647, 1, 15);
