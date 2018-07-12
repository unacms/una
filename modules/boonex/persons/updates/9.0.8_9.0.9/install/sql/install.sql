-- FORMS
UPDATE `sys_form_inputs` SET `required`='1', `checker_func`='date_range', `checker_params`='a:2:{s:3:"min";i:18;s:3:"max";i:99;}', `checker_error`='_bx_persons_form_profile_input_birthday_err' WHERE `object`='bx_person' AND `name`='birthday';
