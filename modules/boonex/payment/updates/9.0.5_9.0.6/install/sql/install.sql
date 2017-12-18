SET @sName = 'bx_payment';


UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = @sName LIMIT 1;


-- FORMS
UPDATE `sys_form_inputs` SET `type`='custom' WHERE `object`='bx_payment_form_processed' AND `name`='client';