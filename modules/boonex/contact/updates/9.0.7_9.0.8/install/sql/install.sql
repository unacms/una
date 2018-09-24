-- FORMS
UPDATE `sys_objects_form` SET `submit_name`='cfc_do_submit' WHERE `object`='bx_contact_contact';

UPDATE `sys_form_inputs` SET `name`='cfc_do_submit' WHERE `object`='bx_contact_contact' AND `name`='do_submit';

UPDATE `sys_form_display_inputs` set `input_name`='cfc_do_submit' WHERE `display_name`='bx_contact_contact_send' AND `input_name`='do_submit';
