SET @sName = 'bx_market';

-- FORMS
UPDATE `sys_form_inputs` SET `info`='_bx_market_form_entry_input_name_inf', `checker_func`='', `checker_error`='' WHERE `object`=@sName AND `name`='name';
