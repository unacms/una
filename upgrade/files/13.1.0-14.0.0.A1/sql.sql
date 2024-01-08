

-- FORMS

INSERT IGNORE INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_labels', 'system', 'list_context', '', '', 0, 'custom', '_sys_form_labels_input_caption_system_list_context', '_sys_form_labels_input_caption_list_context', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT IGNORE INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_labels_select', 'list_context', 2147483647, 1, 4);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-A1' WHERE `version` = '13.1.0' AND `name` = 'system';

