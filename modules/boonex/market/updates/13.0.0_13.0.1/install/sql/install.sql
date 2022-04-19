-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name`='cf';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

UPDATE `sys_form_inputs` SET `type`='price' WHERE `object`='bx_market' AND `name` IN ('price_single', 'price_recurring');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit') AND `input_name`='cf';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'cf', 2147483647, 1, 21),
('bx_market_entry_edit', 'cf', 2147483647, 1, 21);
