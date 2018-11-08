-- FORMS
UPDATE `sys_form_inputs` SET `html`='3' WHERE `object`='bx_market' AND `name`='notes_purchased';

UPDATE `sys_form_inputs` SET `collapsed`='1' WHERE `object`='bx_market' AND `name`='header_beg_single';
UPDATE `sys_form_inputs` SET `collapsed`='1' WHERE `object`='bx_market' AND `name`='header_beg_recurring';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name` IN ('header_beg_notes', 'header_beg_other', 'header_end_notes', 'header_end_other');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'header_beg_notes', '', '', 0, 'block_header', '_bx_market_form_entry_input_sys_header_beg_notes', '_bx_market_form_entry_input_header_beg_notes', '', 0, 1, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_beg_other', '', '', 0, 'block_header', '_bx_market_form_entry_input_sys_header_beg_other', '_bx_market_form_entry_input_header_beg_other', '', 0, 1, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_end_notes', '', '', 0, 'block_end', '_bx_market_form_entry_input_sys_header_end_notes', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_market', 'bx_market', 'header_end_other', '', '', 0, 'block_end', '_bx_market_form_entry_input_sys_header_end_other', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'bx_market_entry_add' AND `input_name` IN ('header_beg_notes', 'notes', 'notes_purchased', 'header_end_notes', 'header_beg_other', 'location', 'subentries', 'header_end_other', 'do_publish');

SET @iOrderAdd = IFNULL((SELECT MAX(`order`) FROM `sys_form_display_inputs` WHERE `display_name`='bx_market_entry_add' LIMIT 1), 0);
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'header_beg_notes', 2147483647, 1, @iOrderAdd + 1),
('bx_market_entry_add', 'notes', 2147483647, 1, @iOrderAdd + 2),
('bx_market_entry_add', 'notes_purchased', 2147483647, 1, @iOrderAdd + 3),
('bx_market_entry_add', 'header_end_notes', 2147483647, 1, @iOrderAdd + 4),
('bx_market_entry_add', 'header_beg_other', 2147483647, 1, @iOrderAdd + 5),
('bx_market_entry_add', 'location', 2147483647, 1, @iOrderAdd + 6),
('bx_market_entry_add', 'subentries', 2147483647, 1, @iOrderAdd + 7),
('bx_market_entry_add', 'header_end_other', 2147483647, 1, @iOrderAdd + 8),
('bx_market_entry_add', 'do_publish', 2147483647, 1, @iOrderAdd + 9);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'bx_market_entry_edit' AND `input_name` IN ('header_beg_notes', 'notes', 'notes_purchased', 'header_end_notes', 'header_beg_other', 'location', 'subentries', 'header_end_other', 'do_submit');

SET @iOrderEdit = IFNULL((SELECT MAX(`order`) FROM `sys_form_display_inputs` WHERE `display_name`='bx_market_entry_edit' LIMIT 1), 0);
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_edit', 'header_beg_notes', 2147483647, 1, @iOrderEdit + 1),
('bx_market_entry_edit', 'notes', 2147483647, 1, @iOrderEdit + 2),
('bx_market_entry_edit', 'notes_purchased', 2147483647, 1, @iOrderEdit + 3),
('bx_market_entry_edit', 'header_end_notes', 2147483647, 1, @iOrderEdit + 4),
('bx_market_entry_edit', 'header_beg_other', 2147483647, 1, @iOrderEdit + 5),
('bx_market_entry_edit', 'location', 2147483647, 1, @iOrderEdit + 6),
('bx_market_entry_edit', 'subentries', 2147483647, 1, @iOrderEdit + 7),
('bx_market_entry_edit', 'header_end_other', 2147483647, 1, @iOrderEdit + 8),
('bx_market_entry_edit', 'do_submit', 2147483647, 1, @iOrderEdit + 9);
