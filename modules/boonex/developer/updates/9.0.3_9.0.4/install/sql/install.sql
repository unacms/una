SET @sName = 'bx_developer';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_developer_search_forms', 'bx_developer_search_forms_fields');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_developer_search_forms', 'Sql', 'SELECT * FROM `sys_objects_search_extended` WHERE 1 ', 'sys_objects_search_extended', 'id', 'module,title', 'active', '', 100, NULL, 'start', '', 'module', 'title', 'like', 'module', 'title', 2147483647, 'BxDevFormsSearchForms', 'modules/boonex/developer/classes/BxDevFormsSearchForms.php'),
('bx_developer_search_forms_fields', 'Sql', 'SELECT * FROM `sys_search_extended_fields` WHERE 1 AND `object`=?', 'sys_search_extended_fields', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'type', 'caption', 'like', '', '', 2147483647, 'BxDevFormsSearchFields', 'modules/boonex/developer/classes/BxDevFormsSearchFields.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_developer_search_forms', 'bx_developer_search_forms_fields');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_developer_search_forms', 'switcher', '', '10%', 0, '', '', 1),
('bx_developer_search_forms', 'title', '_bx_dev_frm_txt_search_forms_gl_title', '40%', 1, '38', '', 2),
('bx_developer_search_forms', 'module', '_bx_dev_frm_txt_search_forms_gl_module', '15%', 0, '13', '', 3),
('bx_developer_search_forms', 'fields', '_bx_dev_frm_txt_search_forms_gl_fields', '15%', 0, '13', '', 4),
('bx_developer_search_forms', 'actions', '', '20%', 0, '', '', 5),

('bx_developer_search_forms_fields', 'order', '', '1%', 0, '', '', 1),
('bx_developer_search_forms_fields', 'switcher', '', '9%', 0, '', '', 2),
('bx_developer_search_forms_fields', 'type', '_bx_dev_frm_txt_search_forms_fields_gl_type', '10%', 0, '', '', 3),
('bx_developer_search_forms_fields', 'caption', '_bx_dev_frm_txt_search_forms_fields_gl_caption', '40%', 1, '38', '', 4),
('bx_developer_search_forms_fields', 'search_type', '_bx_dev_frm_txt_search_forms_fields_gl_search_type', '10%', 0, '', '', 5),
('bx_developer_search_forms_fields', 'search_operator', '_bx_dev_frm_txt_search_forms_fields_gl_search_operator', '10%', 0, '', '', 6),
('bx_developer_search_forms_fields', 'actions', '', '20%', 0, '', '', 7);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_developer_search_forms', 'bx_developer_search_forms_fields');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_developer_search_forms', 'independent', 'add', '_bx_dev_frm_btn_search_forms_create', '', 0, 1),
('bx_developer_search_forms', 'single', 'edit', '', 'pencil', 0, 1),

('bx_developer_search_forms_fields', 'single', 'edit', '', 'pencil', 0, 1),
('bx_developer_search_forms_fields', 'independent', 'reset', '_bx_dev_frm_btn_search_forms_fields_reset', '', 0, 1);


-- FORMS
UPDATE `sys_form_inputs` SET `checker_params`='a:2:{s:3:"min";i:1;s:3:"max";i:100;}' WHERE `object`='bx_developer_forms_prevalue' AND `name`='LKey';

DELETE FROM `sys_objects_form` WHERE `object` IN ('bx_developer_forms_search_form', 'bx_developer_forms_search_fields');
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_developer_forms_search_form', @sName, '_bx_dev_frm_txt_search_forms', '', '', 'do_submit', 'sys_objects_search_extended', 'id', '', '', '', 0, 1, 'BxDevFormView', 'modules/boonex/developer/classes/BxDevFormView.php'),
('bx_developer_forms_search_fields', @sName, '_bx_dev_frm_txt_search_fields_form', '', '', 'do_submit', 'sys_search_extended_fields', 'id', '', '', '', 0, 1, 'BxDevFormView', 'modules/boonex/developer/classes/BxDevFormView.php');

DELETE FROM `sys_form_displays` WHERE `display_name` IN ('bx_developer_forms_search_form_add', 'bx_developer_forms_search_form_edit', 'bx_developer_forms_search_fields_edit');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_developer_forms_search_form_add', @sName, 'bx_developer_forms_search_form', '_bx_dev_frm_txt_search_forms_display_add', 0),
('bx_developer_forms_search_form_edit', @sName, 'bx_developer_forms_search_form', '_bx_dev_frm_txt_search_forms_display_edit', 0),

('bx_developer_forms_search_fields_edit', @sName, 'bx_developer_forms_search_fields', '_bx_dev_frm_txt_search_fields_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object` IN ('bx_developer_forms_search_form', 'bx_developer_forms_search_fields');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_forms_search_form', @sName, 'id', '0', '', 0, 'hidden', '_bx_dev_frm_txt_sys_search_forms_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'object', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_forms_object', '_bx_dev_frm_txt_search_forms_object', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:6;s:3:"max";i:100;}', '_bx_dev_frm_err_forms_object', 'Xss', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'object_content_info', '', '', 0, 'select', '_bx_dev_frm_txt_sys_search_forms_object_content_info', '_bx_dev_frm_txt_search_forms_object_content_info', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_frm_err_forms_object_content_info', 'Xss', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'module', '', '', 0, 'select', '_bx_dev_frm_txt_sys_search_forms_module', '_bx_dev_frm_txt_search_forms_module', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_frm_err_forms_module', 'Xss', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'title', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_forms_title', '_bx_dev_frm_txt_search_forms_title', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:6;s:3:"max";i:100;}', '_bx_dev_frm_err_forms_title', 'Xss', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'class_name', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_forms_class_name', '_bx_dev_frm_txt_search_forms_class_name', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'class_file', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_forms_class_file', '_bx_dev_frm_txt_search_forms_class_file', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'controls', '', 'do_submit,cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'do_submit', '_bx_dev_frm_btn_search_forms_add', '', 0, 'submit', '_bx_dev_frm_btn_sys_search_forms_add', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_developer_forms_search_form', @sName, 'cancel', '_bx_dev_frm_btn_search_forms_cancel', '', 0, 'button', '_bx_dev_frm_btn_sys_search_forms_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),

('bx_developer_forms_search_fields', @sName, 'id', '0', '', 0, 'hidden', '_bx_dev_frm_txt_sys_search_fields_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'object', '', '', 0, 'select', '_bx_dev_frm_txt_sys_search_fields_object', '_bx_dev_frm_txt_search_fields_object', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:6;s:3:"max";i:100;}', '_bx_dev_frm_err_forms_object', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'name', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_fields_name', '_bx_dev_frm_txt_search_fields_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_frm_err_search_fields_name', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'type', '', '', 0, 'select', '_bx_dev_frm_txt_sys_search_fields_type', '_bx_dev_frm_txt_search_fields_type', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_frm_err_search_fields_type', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'caption', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_fields_caption', '_bx_dev_frm_txt_search_fields_caption', '', 1, 0, 0, '', '', '', 'Length', 'a:2:{s:3:"min";i:6;s:3:"max";i:100;}', '_bx_dev_frm_err_search_fields_caption', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'values', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_fields_values', '_bx_dev_frm_txt_search_fields_values', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'search_type', '', '', 0, 'select', '_bx_dev_frm_txt_sys_search_fields_search_type', '_bx_dev_frm_txt_search_fields_search_type', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_frm_err_search_fields_search_type', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'search_value', '', '', 0, 'text', '_bx_dev_frm_txt_sys_search_fields_search_value', '_bx_dev_frm_txt_search_fields_search_value', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'search_operator', '', '', 0, 'select', '_bx_dev_frm_txt_sys_search_fields_search_operator', '_bx_dev_frm_txt_search_fields_search_operator', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_dev_frm_err_search_fields_search_operator', 'Xss', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'controls', '', 'do_submit,cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'do_submit', '_bx_dev_frm_btn_search_fields_save', '', 0, 'submit', '_bx_dev_frm_btn_sys_search_fields_save', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_developer_forms_search_fields', @sName, 'cancel', '_bx_dev_frm_btn_search_fields_cancel', '', 0, 'button', '_bx_dev_frm_btn_sys_search_fields_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_developer_forms_search_form_add', 'bx_developer_forms_search_form_edit', 'bx_developer_forms_search_fields_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_forms_search_form_add', 'object', 2147483647, 1, 1),
('bx_developer_forms_search_form_add', 'object_content_info', 2147483647, 1, 2),
('bx_developer_forms_search_form_add', 'module', 2147483647, 1, 3),
('bx_developer_forms_search_form_add', 'title', 2147483647, 1, 4),
('bx_developer_forms_search_form_add', 'class_name', 2147483647, 1, 5),
('bx_developer_forms_search_form_add', 'class_file', 2147483647, 1, 6),
('bx_developer_forms_search_form_add', 'controls', 2147483647, 1, 7),
('bx_developer_forms_search_form_add', 'do_submit', 2147483647, 1, 8),
('bx_developer_forms_search_form_add', 'cancel', 2147483647, 1, 9),

('bx_developer_forms_search_form_edit', 'id', 2147483647, 1, 1),
('bx_developer_forms_search_form_edit', 'object', 2147483647, 1, 2),
('bx_developer_forms_search_form_edit', 'object_content_info', 2147483647, 1, 3),
('bx_developer_forms_search_form_edit', 'module', 2147483647, 1, 4),
('bx_developer_forms_search_form_edit', 'title', 2147483647, 1, 5),
('bx_developer_forms_search_form_edit', 'class_name', 2147483647, 1, 6),
('bx_developer_forms_search_form_edit', 'class_file', 2147483647, 1, 7),
('bx_developer_forms_search_form_edit', 'controls', 2147483647, 1, 8),
('bx_developer_forms_search_form_edit', 'do_submit', 2147483647, 1, 9),
('bx_developer_forms_search_form_edit', 'cancel', 2147483647, 1, 10),

('bx_developer_forms_search_fields_edit', 'id', 2147483647, 1, 1),
('bx_developer_forms_search_fields_edit', 'object', 2147483647, 1, 2),
('bx_developer_forms_search_fields_edit', 'name', 2147483647, 1, 3),
('bx_developer_forms_search_fields_edit', 'type', 2147483647, 1, 4),
('bx_developer_forms_search_fields_edit', 'caption', 2147483647, 1, 5),
('bx_developer_forms_search_fields_edit', 'values', 2147483647, 1, 6),
('bx_developer_forms_search_fields_edit', 'search_type', 2147483647, 1, 7),
('bx_developer_forms_search_fields_edit', 'search_value', 2147483647, 1, 8),
('bx_developer_forms_search_fields_edit', 'search_operator', 2147483647, 1, 9),
('bx_developer_forms_search_fields_edit', 'controls', 2147483647, 1, 10),
('bx_developer_forms_search_fields_edit', 'do_submit', 2147483647, 1, 11),
('bx_developer_forms_search_fields_edit', 'cancel', 2147483647, 1, 12);
