UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_events' LIMIT 1;


-- FORMS
UPDATE `sys_form_inputs` SET `required`='0', `checker_func`='' WHERE `object`='bx_event' AND `name`='date_end';
UPDATE `sys_form_inputs` SET `required`='0', `checker_func`='' WHERE `object`='bx_event' AND `name`='date_start';
