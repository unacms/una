SET @sName = 'bx_forum';


-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`=@sName AND `name`='allow_view_to';