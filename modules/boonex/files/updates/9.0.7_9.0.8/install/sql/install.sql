-- FORMS
UPDATE `sys_form_inputs` SET `caption_system`='_bx_files_form_entry_input_sys_attachment', `caption`='_bx_files_form_entry_input_attachment', `required`='1', `checker_func`='avail', `checker_error`='_bx_files_form_entry_input_attachment' WHERE `object`='bx_files' AND `name`='attachments';
UPDATE `sys_form_inputs` SET `caption_system`='_bx_files_form_entry_input_sys_attachments', `caption`='_bx_files_form_entry_input_attachments', `required`='1', `checker_func`='avail', `checker_error`='_bx_files_form_entry_input_attachments' WHERE `object`='bx_files_upload' AND `name`='attachments';
