-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object` IN ('bx_files', 'bx_files_upload') AND `name`='allow_view_to';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`='bx_files' WHERE `Name`='bx_files';