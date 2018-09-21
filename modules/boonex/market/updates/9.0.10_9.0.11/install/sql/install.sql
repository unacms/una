-- TABLES
ALTER TABLE `bx_market_cmts` CHANGE `cmt_author_id` `cmt_author_id` INT( 11 ) NOT NULL DEFAULT '0';


-- FORMS
UPDATE `sys_form_inputs` SET `info`='_bx_market_form_entry_input_notes_inf' WHERE `object`='bx_market' AND `name`='notes';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name`='notes_purchased';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'notes_purchased', '', '', 0, 'textarea', '_bx_market_form_entry_input_sys_notes_purchased', '_bx_market_form_entry_input_notes_purchased', '_bx_market_form_entry_input_notes_purchased_inf', 0, 0, 0, '', '', '', '', '', '', 'XssHtml', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit') AND `input_name`='notes_purchased';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'notes_purchased', 2147483647, 1, 24),
('bx_market_entry_edit', 'notes_purchased', 2147483647, 1, 24);
