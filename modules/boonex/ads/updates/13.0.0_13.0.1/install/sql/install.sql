-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_ads' AND `name`='cf';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_ads', 'bx_ads', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

UPDATE `sys_form_inputs` SET `type`='price' WHERE `object`='bx_ads' AND `name`='price';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_ads_entry_price_add', 'bx_ads_entry_price_edit', 'bx_ads_entry_price_year_add', 'bx_ads_entry_price_year_edit') AND `input_name`='cf';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_price_add', 'cf', 2147483647, 1, 15),
('bx_ads_entry_price_edit', 'cf', 2147483647, 1, 14),

('bx_ads_entry_price_year_add', 'cf', 2147483647, 1, 16),
('bx_ads_entry_price_year_edit', 'cf', 2147483647, 1, 15);


-- REPORTS
UPDATE `sys_objects_report` SET `object_comment`='bx_ads_notes' WHERE `name`='bx_ads';
