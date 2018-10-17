-- FORMS
UPDATE `sys_form_displays` SET `title`='_bx_photos_form_upload_display_upload' WHERE `object`='bx_photos_upload' AND `display_name`='bx_photos_entry_upload';

UPDATE `sys_form_inputs` SET `caption_system`='_bx_photos_form_entry_input_sys_picture', `caption`='_bx_photos_form_entry_input_picture', `required`='1', `checker_func`='avail', `checker_error`='_bx_photos_form_entry_input_picture_err' WHERE `object`='bx_photos' AND `name`='pictures';
UPDATE `sys_form_inputs` SET `caption_system`='_bx_photos_form_entry_input_sys_pictures', `caption`='_bx_photos_form_entry_input_pictures', `required`='1', `checker_func`='avail', `checker_error`='_bx_photos_form_entry_input_pictures_err' WHERE `object`='bx_photos_upload' AND `name`='pictures';
