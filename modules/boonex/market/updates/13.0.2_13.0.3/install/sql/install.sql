-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `ext_allow`='jpg,jpeg,jpe,gif,png,svg' WHERE `object`='bx_market_photos';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name`='cover_raw';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'cover_raw', '', '', 0, 'textarea', '_bx_market_form_entry_input_sys_cover_raw', '_bx_market_form_entry_input_cover_raw', '_bx_market_form_entry_input_cover_raw_inf', 0, 0, 0, '', '', '', '', '', '', 'XssHtml', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit') AND `input_name`='cover_raw';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'cover_raw', 2147483647, 0, 4),
('bx_market_entry_edit', 'cover_raw', 2147483647, 0, 4);
