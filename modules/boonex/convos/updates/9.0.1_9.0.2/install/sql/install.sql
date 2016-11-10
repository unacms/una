-- FORMS
DELETE FROM `sys_form_displays` WHERE `object`='bx_convos' AND `display_name`='bx_convos_entry_edit';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_convos', 'bx_convos_entry_edit', 'bx_convos', 0, '_bx_cnv_form_entry_display_edit');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_convos' AND `name`='allow_edit';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_convos', 'bx_convos', 'allow_edit', 1, '', 0, 'switcher', '_bx_cnv_form_entry_input_sys_allow_edit', '_bx_cnv_form_entry_input_allow_edit', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_convos_entry_add' AND `input_name`='allow_edit';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_convos_entry_add', 'allow_edit', 2147483647, 1, 4);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_convos_entry_edit';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_convos_entry_edit', 'delete_confirm', 2147483647, 0, 1),
('bx_convos_entry_edit', 'recipients', 2147483647, 1, 2),
('bx_convos_entry_edit', 'text', 2147483647, 1, 3),
('bx_convos_entry_edit', 'attachments', 2147483647, 1, 4),
('bx_convos_entry_edit', 'allow_edit', 2147483647, 1, 5),
('bx_convos_entry_edit', 'submit_block', 2147483647, 1, 6),
('bx_convos_entry_edit', 'do_submit', 2147483647, 1, 7),
('bx_convos_entry_edit', 'submit_text', 2147483647, 0, 8),
('bx_convos_entry_edit', 'draft_id', 2147483647, 0, 9);


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_convos@modules/boonex/convos/|std-icon.svg' WHERE `name`='bx_convos';
UPDATE `sys_std_widgets` SET `icon`='bx_convos@modules/boonex/convos/|std-icon.svg' WHERE `module`='bx_convos' AND `caption`='_bx_cnv';