-- FORMS
UPDATE `sys_form_inputs` SET `type`='location', `caption`='_sys_form_input_location' WHERE `object`='bx_market' AND `name`='location';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_market' AND `name` IN ('warning_single', 'warning_recurring');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'warning_single', '_bx_market_err_not_accept_payments_single', '', 0, 'value', '_bx_market_form_entry_input_sys_warning_single', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'warning_recurring', '_bx_market_err_not_accept_payments_recurring', '', 0, 'value', '_bx_market_form_entry_input_sys_warning_recurring', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_market_entry_add', 'bx_market_entry_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_market_entry_add', 'title', 2147483647, 1, 2),
('bx_market_entry_add', 'name', 2147483647, 1, 3),
('bx_market_entry_add', 'cat', 2147483647, 1, 4),
('bx_market_entry_add', 'text', 2147483647, 1, 5),
('bx_market_entry_add', 'pictures', 2147483647, 1, 6),
('bx_market_entry_add', 'files', 2147483647, 1, 7),
('bx_market_entry_add', 'header_beg_single', 2147483647, 1, 8),
('bx_market_entry_add', 'price_single', 2147483647, 1, 10),
('bx_market_entry_add', 'header_end_single', 2147483647, 1, 11),
('bx_market_entry_add', 'header_beg_recurring', 2147483647, 1, 12),
('bx_market_entry_add', 'warning_recurring', 2147483647, 1, 13),
('bx_market_entry_add', 'duration_recurring', 2147483647, 1, 14),
('bx_market_entry_add', 'price_recurring', 2147483647, 1, 15),
('bx_market_entry_add', 'header_end_recurring', 2147483647, 1, 16),
('bx_market_entry_add', 'header_beg_privacy', 2147483647, 1, 17),
('bx_market_entry_add', 'allow_view_to', 2147483647, 1, 18),
('bx_market_entry_add', 'allow_purchase_to', 2147483647, 1, 19),
('bx_market_entry_add', 'allow_comment_to', 2147483647, 1, 20),
('bx_market_entry_add', 'allow_vote_to', 2147483647, 1, 21),
('bx_market_entry_add', 'header_end_privacy', 2147483647, 1, 22),
('bx_market_entry_add', 'notes', 2147483647, 1, 23),
('bx_market_entry_add', 'location', 2147483647, 1, 24),
('bx_market_entry_add', 'do_submit', 2147483647, 0, 25),
('bx_market_entry_add', 'do_publish', 2147483647, 1, 26),

('bx_market_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_market_entry_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_market_entry_edit', 'title', 2147483647, 1, 3),
('bx_market_entry_edit', 'name', 2147483647, 1, 4),
('bx_market_entry_edit', 'cat', 2147483647, 1, 5),
('bx_market_entry_edit', 'text', 2147483647, 1, 6),
('bx_market_entry_edit', 'pictures', 2147483647, 1, 7),
('bx_market_entry_edit', 'files', 2147483647, 1, 8),
('bx_market_entry_edit', 'header_beg_single', 2147483647, 1, 9),
('bx_market_entry_edit', 'warning_single', 2147483647, 1, 10),
('bx_market_entry_edit', 'price_single', 2147483647, 1, 11),
('bx_market_entry_edit', 'header_end_single', 2147483647, 1, 12),
('bx_market_entry_edit', 'header_beg_recurring', 2147483647, 1, 13),
('bx_market_entry_edit', 'warning_recurring', 2147483647, 1, 14),
('bx_market_entry_edit', 'duration_recurring', 2147483647, 1, 15),
('bx_market_entry_edit', 'price_recurring', 2147483647, 1, 16),
('bx_market_entry_edit', 'header_end_recurring', 2147483647, 1, 17),
('bx_market_entry_edit', 'header_beg_privacy', 2147483647, 1, 18),
('bx_market_entry_edit', 'allow_view_to', 2147483647, 1, 19),
('bx_market_entry_edit', 'allow_purchase_to', 2147483647, 1, 20),
('bx_market_entry_edit', 'allow_comment_to', 2147483647, 1, 21),
('bx_market_entry_edit', 'allow_vote_to', 2147483647, 1, 22),
('bx_market_entry_edit', 'header_end_privacy', 2147483647, 1, 23),
('bx_market_entry_edit', 'notes', 2147483647, 1, 24),
('bx_market_entry_edit', 'location', 2147483647, 1, 25),
('bx_market_entry_edit', 'do_submit', 2147483647, 1, 26);


-- PRE-VALUES
DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_market_durations' AND `Value`='day';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_market_durations', 'day', 1, '_bx_market_cat_duration_day', '');